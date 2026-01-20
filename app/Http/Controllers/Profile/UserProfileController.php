<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Profile\UserProfileService;

class UserProfileController extends Controller
{
    public function updateProfile(Request $request, $id, UserProfileService $service)
    {

        $request->validate([
            'address' => 'sometimes|string|max:255', // Change mix to max
            'phone' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:255',
            'birthday' => 'sometimes|date',           // Dates don't usually need a max length
            'gender' => 'sometimes|string|max:25',
            'profile' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:2048',
        ]);
        $profile = $service->update($id, $request);

        return response()->json(
            [
                'data' => $profile,
                'message' => 'update profile successfully'
            ],
            201
        );
    }

    public function getProfile(UserProfileService $service)
    {
        $profile = $service->getProfile();

        if (!$profile) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'data' => $profile,
            'message' => 'Profile retrieved successfully'
        ], 200);
    }
}
