<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get the authenticated user's profile (name, email, phone).
     */
    public function profile(): JsonResponse
    {
        return response()->json(new UserResource(auth()->user()));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request): JsonResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'number' => 'sometimes|nullable|string|max:30',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    /**
     * Create a new employee account with a temporary password.
     * Admin, owner, and developer only.
     */
    public function createEmployee(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'nullable|string|max:255',
        ]);

        $temporaryPassword = Str::random(12);

        $employee = User::create([
            'name' => $validated['name'] ?? 'New Employee',
            'email' => $validated['email'],
            'password' => Hash::make($temporaryPassword),
            'role' => 'employee',
        ]);

        return response()->json([
            'message' => 'Employee created successfully',
            'employee' => new EmployeeResource($employee),
            'temporary_password' => $temporaryPassword,
        ], 201);
    }

    /**
     * List all employees (role = employee).
     * Admin, owner, and developer only.
     */
    public function listEmployees(): JsonResponse
    {
        $employees = User::where('role', 'employee')
            ->orderBy('name')
            ->get();

        return response()->json(EmployeeResource::collection($employees));
    }

    /**
     * Delete an employee account.
     * Admin, owner, and developer only.
     * Cannot delete users with admin/owner/developer roles.
     */
    public function deleteEmployee(int|string $id): JsonResponse
    {
        $employee = User::find($id);

        if (!$employee) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Prevent deleting anyone above or at the admin level through this endpoint
        if ($employee->role !== 'employee') {
            return response()->json([
                'error' => 'You can only delete employee accounts through this endpoint'
            ], 403);
        }

        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully']);
    }

    /**
     * Update an employee's profile (admin managing their staff).
     * Admin, owner, and developer only.
     */
    public function updateEmployee(Request $request, int|string $id): JsonResponse
    {
        $employee = User::where('role', 'employee')->find($id);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $employee->id,
            'number' => 'sometimes|nullable|string|max:30',
        ]);

        $employee->update($validated);

        return response()->json([
            'message' => 'Employee updated successfully',
            'employee' => new EmployeeResource($employee),
        ]);
    }

    /**
     * Delete the authenticated user's own account.
     * Any authenticated user can delete themselves.
     */
    public function deleteAccount(): JsonResponse
    {
        $user = auth()->user();
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }
}
