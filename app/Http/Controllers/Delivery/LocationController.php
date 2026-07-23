<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Save/update Delivery Man live GPS location
     * and mark him online.
     */
    public function update(Request $request)
    {
        $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $user = $request->user();

        if (!$user || $user->role !== 'delivery') {
            abort(403);
        }

        $profile = $user->deliveryProfile;

        if (!$profile || $profile->approval_status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Delivery account is not approved.',
            ], 403);
        }

        $profile->update([
            'is_online'         => true,
            'last_seen_at'      => now(),
            'current_latitude'  => $request->latitude,
            'current_longitude' => $request->longitude,
            'location_updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully.',
        ]);
    }

    /**
     * Mark Delivery Man offline.
     */
    public function offline(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'delivery') {
            abort(403);
        }

        $profile = $user->deliveryProfile;

        if ($profile) {
            $profile->update([
                'is_online'    => false,
                'last_seen_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'You are now offline.',
        ]);
    }
}