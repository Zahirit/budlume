<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryManController extends Controller
{
    /**
     * Display delivery partner applications.
     */
    public function index(Request $request)
    {
        $query = DeliveryProfile::with('user')
            ->latest();

        // Filter by approval status
        if ($request->filled('status')) {
            $query->where(
                'approval_status',
                $request->status
            );
        }

        $deliveryMen = $query
            ->paginate(15)
            ->withQueryString();

        // Summary counts
        $pendingCount = DeliveryProfile::where(
            'approval_status',
            'pending'
        )->count();

        $approvedCount = DeliveryProfile::where(
            'approval_status',
            'approved'
        )->count();

        $rejectedCount = DeliveryProfile::where(
            'approval_status',
            'rejected'
        )->count();

        return view(
            'admin.delivery-men.index',
            compact(
                'deliveryMen',
                'pendingCount',
                'approvedCount',
                'rejectedCount'
            )
        );
    }

    /**
     * Display one delivery partner application.
     */
    public function show(DeliveryProfile $deliveryMan)
    {
        $deliveryMan->load([
            'user',
            'approvedBy',
        ]);

        return view(
            'admin.delivery-men.show',
            compact('deliveryMan')
        );
    }

    /**
     * Approve delivery partner.
     */
    public function approve(DeliveryProfile $deliveryMan)
    {
        $deliveryMan->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'is_available' => true,
        ]);

        return redirect()
            ->route(
                'admin.delivery-men.show',
                $deliveryMan
            )
            ->with(
                'success',
                'Delivery partner approved successfully.'
            );
    }

    /**
     * Reject delivery partner.
     */
    public function reject(DeliveryProfile $deliveryMan)
    {
        $deliveryMan->update([
            'approval_status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
            'is_available' => false,
        ]);

        return redirect()
            ->route(
                'admin.delivery-men.show',
                $deliveryMan
            )
            ->with(
                'success',
                'Delivery partner application rejected.'
            );
    }
}