<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Place a new order (any authenticated user).
     *
     * Expected payload:
     * {
     *   "endereco_id": 1,
     *   "notes": "Leave at the door",
     *   "items": [
     *     { "product_id": 1, "quantity": 2 },
     *     { "product_id": 5, "quantity": 1 }
     *   ]
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endereco_id' => 'nullable|exists:enderecos,id',
            'payment_method' => 'required|string|in:pix,cash',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Verify the address belongs to the authenticated user
        if (!empty($validated['endereco_id'])) {
            $addressBelongsToUser = auth()->user()
                ->enderecos()
                ->where('id', $validated['endereco_id'])
                ->exists();

            if (!$addressBelongsToUser) {
                return response()->json(['error' => 'Address not found'], 404);
            }
        }

        // Collect product IDs and load them in a single query
        $productIds = collect($validated['items'])->pluck('product_id')->unique();
        $products = ProductModel::whereIn('id', $productIds)->get()->keyBy('id');

        // Validate stock availability before creating anything
        foreach ($validated['items'] as $item) {
            $product = $products->get($item['product_id']);

            if (!$product) {
                return response()->json([
                    'error' => "Product ID {$item['product_id']} not found"
                ], 404);
            }

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'error' => "Insufficient stock for '{$product->name}'. Available: {$product->stock}, requested: {$item['quantity']}"
                ], 422);
            }
        }

        // Create the order inside a transaction
        $order = DB::transaction(function () use ($validated, $products) {
            $lockedProducts = ProductModel::whereIn('id', $products->keys())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($validated['items'] as $item) {
                $product = $lockedProducts->get($item['product_id']);

                if (!$product || $product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for '{$product?->name}', available: {$product?->stock}, requested: {$item['quantity']}",
                    ]);
                }
            }

            $order = OrderModel::create([
                'user_id' => auth()->id(),
                'endereco_id' => $validated['endereco_id'] ?? null,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $product = $lockedProducts->get($item['product_id']);

                $orderItem = OrderItemModel::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'discount' => $product->discount,
                ]);

                $product->decrement('stock', $item['quantity']);
                $total += $orderItem->subtotal;
            }

            $order->update(['total' => $total]);

            return $order;
        });

        $order->load('items.product', 'endereco');

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => new OrderResource($order),
        ], 201);
    }

    /**
     * Get the authenticated user's orders.
     */
    public function myOrders(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        $orders = auth()->user()
            ->orders()
            ->with('items.product', 'endereco')
            ->latest()
            ->paginate($perPage);

        return response()->json(
            OrderResource::collection($orders)->response()->getData(true)
        );
    }

    /**
     * Get all orders (employee, admin, owner, developer).
     */
    public function allOrders(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        $orders = OrderModel::with('items.product', 'endereco', 'user')
            ->latest()
            ->paginate($perPage);

        // Include the user info in each order for staff views
        $data = OrderResource::collection($orders)->response()->getData(true);

        // Enrich with user name/email for staff
        foreach ($data['data'] as $index => &$orderData) {
            $order = $orders->items()[$index] ?? null;
            if ($order && $order->relationLoaded('user')) {
                $orderData['user'] = [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ];
            }
        }

        return response()->json($data);
    }

    /**
     * Get orders filtered by status.
     * Employee, admin, owner, and developer only.
     *
     * Query params: ?status=pending&per_page=15
     */
    public function filtered(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $perPage = $request->integer('per_page', 15);

        $orders = OrderModel::with('items.product', 'endereco', 'user')
            ->where('status', $request->status)
            ->latest()
            ->paginate($perPage);

        $data = OrderResource::collection($orders)->response()->getData(true);

        foreach ($data['data'] as $index => &$orderData) {
            $order = $orders->items()[$index] ?? null;
            if ($order && $order->relationLoaded('user')) {
                $orderData['user'] = [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ];
            }
        }

        return response()->json($data);
    }

    /**
     * Get one order for employee/admin management.
     */
    public function show(int|string $id): JsonResponse
    {
        $order = OrderModel::with('user', 'items.product', 'endereco')->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json(new OrderResource($order));
    }

    /**
     * Update order status.
     * Employee, admin, owner, and developer only.
     *
    * Business rules: reserve stock when an order is created, restore it when
    * an order is cancelled, and count sales when an order is delivered.
     */
    public function updateStatus(Request $request, int|string $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order = OrderModel::with('items')->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $newStatus = $request->status;

        if (!$order->canTransitionTo($newStatus)) {
            return response()->json([
                'error' => "Cannot transition from '{$order->status}' to '{$newStatus}'"
            ], 422);
        }

        DB::transaction(function () use ($order, $newStatus) {
            $products = ProductModel::whereIn('id', $order->items->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($order->items as $item) {
                $product = $products->get($item->product_id);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' => "Product ID {$item->product_id} not found",
                    ]);
                }

                if ($newStatus === 'cancelled') {
                    $product->increment('stock', $item->quantity);
                }

                if ($newStatus === 'delivered') {
                    $product->increment('total_sold', $item->quantity);
                }
            }

            $order->update(['status' => $newStatus]);
        });

        $order->load('items.product', 'endereco');

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => new OrderResource($order),
        ]);
    }
}
