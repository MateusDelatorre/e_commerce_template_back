<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $order = OrderModel::create([
                'user_id' => auth()->id(),
                'endereco_id' => $validated['endereco_id'] ?? null,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);

                $orderItem = OrderItemModel::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'discount' => $product->discount,
                ]);

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
     * Update order status.
     * Employee, admin, owner, and developer only.
     *
     * Business rule: when transitioning to 'delivered', deduct stock and
     * increment total_sold on each product.
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
            // When order is completed (delivered), deduct stock and track sales
            if ($newStatus === 'delivered') {
                foreach ($order->items as $item) {
                    ProductModel::where('id', $item->product_id)->update([
                        'stock' => DB::raw("CASE WHEN stock >= {$item->quantity} THEN stock - {$item->quantity} ELSE 0 END"),
                        'total_sold' => DB::raw("total_sold + {$item->quantity}"),
                    ]);
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
