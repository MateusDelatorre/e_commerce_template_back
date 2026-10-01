<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListResource;
use App\Http\Resources\AdminProductResource;
use App\Http\Resources\ProductResource;
use App\Models\ProductModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products (paginated).
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        $products = ProductModel::query()->latest()->paginate($perPage);

        return response()->json(
            ProductListResource::collection($products)->response()->getData(true)
        );
    }

    /**
     * Display products for employee catalog management.
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);
        $products = ProductModel::query()->latest()->paginate($perPage);

        return response()->json(
            AdminProductResource::collection($products)->response()->getData(true)
        );
    }

    /**
     * Get newly added products (paginated, sorted by newest first).
     */
    public function newProducts(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 12);

        $products = ProductModel::query()
            ->latest()
            ->paginate($perPage);

        return response()->json(
            ProductListResource::collection($products)->response()->getData(true)
        );
    }

    /**
     * Get products that have active offers/discounts (paginated).
     */
    public function withOffers(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 12);

        $products = ProductModel::query()
            ->where('discount', '>', 0)
            ->orderByDesc('discount')
            ->paginate($perPage);

        return response()->json(
            ProductListResource::collection($products)->response()->getData(true)
        );
    }

    /**
     * Get featured products.
     */
    public function featured(): JsonResponse
    {
        $products = ProductModel::query()
            ->where('is_featured', true)
            ->latest()
            ->get();

        return response()->json(ProductListResource::collection($products));
    }

    /**
     * Display the specified product (full detail).
     */
    public function show(Request $request, int|string $id): JsonResponse
    {
        $product = ProductModel::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(new ProductResource($product));
    }

    /**
     * Search products by name with optional sorting.
     * Sort options: price_asc, price_desc, name_asc, name_desc, best_sellers
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:1',
            'sort' => 'nullable|string|in:price_asc,price_desc,name_asc,name_desc,best_sellers',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = ProductModel::query()
            ->where('name', 'like', '%' . $request->q . '%');

        // Apply sort
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'best_sellers':
                $query->orderBy('total_sold', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $perPage = $request->integer('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json(
            ProductListResource::collection($products)->response()->getData(true)
        );
    }

    /**
     * Store a newly created product in storage.
     * Employee, admin, owner, and developer only.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'is_featured' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product = ProductModel::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    /**
     * Update the specified product in storage.
     * Employee, admin, owner, and developer only.
     */
    public function update(Request $request, int|string $id): JsonResponse
    {
        $product = ProductModel::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'discount' => 'sometimes|numeric|min:0|max:100',
            'is_featured' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete previous image if exists
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    /**
     * Upload or replace image for an existing product.
     */
    public function uploadImage(Request $request, int|string $id): JsonResponse
    {
        $product = ProductModel::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Delete old image if exists
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $path = $request->file('image')->store('products', 'public');
        $product->update(['image_path' => $path]);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'image_url' => $product->image_url,
            'product' => $product,
        ]);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(int|string $id): JsonResponse
    {
        $product = ProductModel::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Delete associated image file
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
