<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryOffer;
use App\Services\DeliveryAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryOfferController extends Controller
{
    /**
     * Accept a delivery offer.
     */
    public function accept(
        Request $request,
        DeliveryOffer $offer
    ): RedirectResponse {

        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'delivery' ||
            $offer->delivery_man_id !== $user->id
        ) {
            abort(403);
        }

        if (
            $offer->status !== 'pending' ||
            now()->greaterThan($offer->expires_at)
        ) {
            return back()->with(
                'error',
                'This delivery offer is no longer available.'
            );
        }

        DB::transaction(function () use ($offer) {

            $offer->update([
                'status' => 'accepted',
                'responded_at' => now(),
            ]);

            $offer->order->update([
                'delivery_man_id' => $offer->delivery_man_id,
                'delivery_status' => 'assigned',
                'assigned_at' => now(),
                'accepted_at' => now(),
            ]);

            DeliveryOffer::query()
                ->where('order_id', $offer->order_id)
                ->where('id', '!=', $offer->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'expired',
                    'responded_at' => now(),
                ]);
        });

        return redirect()
            ->route('delivery.dashboard')
            ->with(
                'success',
                'Delivery accepted successfully.'
            );
    }


    /**
     * Reject a delivery offer and offer it
     * to the next eligible delivery partner.
     */
    public function reject(
        Request $request,
        DeliveryOffer $offer,
        DeliveryAssignmentService $assignmentService
    ): RedirectResponse {

        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'delivery' ||
            $offer->delivery_man_id !== $user->id
        ) {
            abort(403);
        }

        if ($offer->status !== 'pending') {
            return back()->with(
                'error',
                'This delivery offer is no longer available.'
            );
        }

        $offer->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        $assignmentService->offerOrder(
            $offer->order
        );

        return redirect()
            ->route('delivery.dashboard')
            ->with(
                'success',
                'Offer rejected. The next available delivery partner will be contacted.'
            );
    }
}