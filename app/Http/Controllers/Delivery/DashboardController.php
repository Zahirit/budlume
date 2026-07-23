<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryOffer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the Delivery Partner Dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Only Delivery Partners
        |--------------------------------------------------------------------------
        */

        if (!$user || $user->role !== 'delivery') {
            abort(403);
        }

        $deliveryProfile = $user->deliveryProfile;

        /*
        |--------------------------------------------------------------------------
        | Delivery Profile Must Exist
        |--------------------------------------------------------------------------
        */

        if (!$deliveryProfile) {
            abort(403, 'Delivery profile not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Pending Approval
        |--------------------------------------------------------------------------
        */

        if ($deliveryProfile->approval_status === 'pending') {
            return redirect()
                ->route('delivery.pending');
        }

        /*
        |--------------------------------------------------------------------------
        | Rejected
        |--------------------------------------------------------------------------
        */

        if ($deliveryProfile->approval_status === 'rejected') {
            abort(
                403,
                'Your delivery partner application has been rejected.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Approved Only
        |--------------------------------------------------------------------------
        */

        if ($deliveryProfile->approval_status !== 'approved') {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Current Active Delivery Offer
        |--------------------------------------------------------------------------
        */

        $activeOffer = DeliveryOffer::query()
            ->with('order')
            ->where('delivery_man_id', $user->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->orderBy('offered_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Show Delivery Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'delivery.dashboard',
            compact(
                'user',
                'deliveryProfile',
                'activeOffer'
            )
        );
    }
}