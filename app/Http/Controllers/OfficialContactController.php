<?php

namespace App\Http\Controllers;

use App\Models\OfficialContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfficialContactController extends Controller
{
    /**
     * Get official WhatsApp contact.
     * Public endpoint.
     */
    public function getWhatsApp(): JsonResponse
    {
        $contact = OfficialContact::where('is_active', true)
            ->whereNotNull('whatsapp_number')
            ->where('whatsapp_number', '!=', '')
            ->latest()
            ->first();

        if (!$contact) {
            return response()->json([
                'message' => 'Official WhatsApp contact not found.'
            ], 404);
        }

        return response()->json([
            'name'            => $contact->name,
            'whatsapp_number' => $contact->whatsapp_number,
        ]);
    }

    /**
     * Get official Email contact.
     * Public endpoint.
     */
    public function getEmail(): JsonResponse
    {
        $contact = OfficialContact::where('is_active', true)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->latest()
            ->first();

        if (!$contact) {
            return response()->json([
                'message' => 'Official email contact not found.'
            ], 404);
        }

        return response()->json([
            'name'  => $contact->name,
            'email' => $contact->email,
        ]);
    }

    /**
     * List all official contacts (Admin/Employee).
     */
    public function index(): JsonResponse
    {
        $contacts = OfficialContact::orderByDesc('is_active')->orderBy('id')->get();
        return response()->json($contacts);
    }

    /**
     * Create a new official contact (Admin/Owner).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'is_active'       => 'nullable|boolean',
        ]);

        $contact = OfficialContact::create($validated);

        return response()->json([
            'message' => 'Official contact created successfully.',
            'contact' => $contact,
        ], 201);
    }

    /**
     * Update an official contact (Admin/Owner).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $contact = OfficialContact::findOrFail($id);

        $validated = $request->validate([
            'name'            => 'sometimes|required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'is_active'       => 'nullable|boolean',
        ]);

        $contact->update($validated);

        return response()->json([
            'message' => 'Official contact updated successfully.',
            'contact' => $contact,
        ]);
    }

    /**
     * Delete an official contact (Admin/Owner).
     */
    public function destroy(int $id): JsonResponse
    {
        $contact = OfficialContact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'message' => 'Official contact deleted successfully.',
        ]);
    }
}
