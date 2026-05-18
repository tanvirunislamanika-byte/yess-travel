<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|max:255',
            'device_type' => 'nullable|string|max:50',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        $deviceToken = DeviceToken::updateOrCreate(
            ['token' => $validated['token']],
            [
                'user_id' => $user->id,
                'device_type' => $validated['device_type'] ?? $request->input('platform'),
                'device_name' => $validated['device_name'] ?? null,
                'last_used_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Device token registered successfully.',
            'data' => $deviceToken,
        ], 201);
    }

    public function destroy(Request $request, DeviceToken $deviceToken)
    {
        if ($deviceToken->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this device token.',
            ], 403);
        }

        $deviceToken->delete();

        return response()->json([
            'success' => true,
            'message' => 'Device token removed successfully.',
        ]);
    }
}

