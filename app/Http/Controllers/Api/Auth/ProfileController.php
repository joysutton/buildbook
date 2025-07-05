<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function show(): JsonResponse
    {
        $user = auth()->user();
        
        return response()->json([
            'user' => $user->only(['id', 'username', 'email', 'handle', 'bio', 'created_at']),
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = auth()->user();
        
        $user->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->only(['id', 'username', 'email', 'handle', 'bio', 'created_at']),
        ]);
    }

    /**
     * Delete the authenticated user's account.
     */
    public function destroy(): JsonResponse
    {
        $user = auth()->user();
        
        // Revoke all tokens
        $user->tokens()->delete();
        
        // Delete the user
        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully',
        ]);
    }
}
