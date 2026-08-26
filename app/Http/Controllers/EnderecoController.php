<?php

namespace App\Http\Controllers;

use App\Models\EnderecoModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    /**
     * Display a listing of the user's addresses.
     */
    public function index(): JsonResponse
    {
        // Retrieve authenticated user from JWT token to fetch only their addresses
        $enderecos = auth()->user()->enderecos()->latest()->get();

        return response()->json($enderecos);
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'addressName'       => 'required|string|max:255',
            'receiverName'      => 'required|string|max:255',
            'streetName'        => 'required|string|max:255',
            'number'            => 'required|string|max:50',
            'addressComplement' => 'nullable|string|max:255',
            'city'              => 'required|string|max:255',
            'country'           => 'required|string|max:255',
            'state'             => 'required|string|max:255',
            'cep'               => 'required|string|max:20',
            'phone'             => 'required|string|max:30',
        ]);

        // Retrieve authenticated user from JWT token and attach the new address to them
        $endereco = auth()->user()->enderecos()->create($validated);

        return response()->json([
            'message'  => 'Address created successfully',
            'endereco' => $endereco,
        ], 201);
    }

    /**
     * Display the specified address.
     */
    public function show(int|string $id): JsonResponse
    {
        // Retrieve authenticated user from JWT token to ensure address belongs to them
        $endereco = auth()->user()->enderecos()->find($id);

        if (!$endereco) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        return response()->json($endereco);
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, int|string $id): JsonResponse
    {
        // Retrieve authenticated user from JWT token to ensure ownership before updating
        $endereco = auth()->user()->enderecos()->find($id);

        if (!$endereco) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        $validated = $request->validate([
            'addressName'       => 'sometimes|required|string|max:255',
            'receiverName'      => 'sometimes|required|string|max:255',
            'streetName'        => 'sometimes|required|string|max:255',
            'number'            => 'sometimes|required|string|max:50',
            'addressComplement' => 'nullable|string|max:255',
            'city'              => 'sometimes|required|string|max:255',
            'country'           => 'sometimes|required|string|max:255',
            'state'             => 'sometimes|required|string|max:255',
            'cep'               => 'sometimes|required|string|max:20',
            'phone'             => 'sometimes|required|string|max:30',
        ]);

        $endereco->update($validated);

        return response()->json([
            'message'  => 'Address updated successfully',
            'endereco' => $endereco,
        ]);
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(int|string $id): JsonResponse
    {
        // Retrieve authenticated user from JWT token to ensure ownership before deleting
        $endereco = auth()->user()->enderecos()->find($id);

        if (!$endereco) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        $endereco->delete();

        return response()->json(['message' => 'Address deleted successfully']);
    }
}
