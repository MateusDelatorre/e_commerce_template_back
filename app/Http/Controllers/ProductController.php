<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): JsonResponse
    {
        $products = ProductModel::query()->latest()->get();

        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
     * Display the specified product.
     */
    public function show(int|string $id): JsonResponse
    {
        $product = ProductModel::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, int|string $id): JsonResponse
    {
        $product = ProductModel::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price'       => 'sometimes|required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            'message'   => 'Image uploaded successfully',
            'image_url' => $product->image_url,
            'product'   => $product,
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
