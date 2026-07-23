<?php

namespace App\Services;

use App\Models\DeliveryOffer;
use App\Models\DeliveryProfile;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class DeliveryAssignmentService
{
    /**
     * Send an order offer to the best eligible delivery partner.
     */
    public function offerOrder(Order $order): ?DeliveryOffer
    {
        /*
        |--------------------------------------------------------------------------
        | Order must have delivery coordinates
        |--------------------------------------------------------------------------
        */
        $setting = Setting::query()->first();

        if (
            !$setting ||
            $setting->store_latitude === null ||
            $setting->store_longitude === null
        ) {
            return null;
        }

        $storeLatitude = (float) $setting->store_latitude;
        $storeLongitude = (float) $setting->store_longitude;

        /*
        |--------------------------------------------------------------------------
        | Find eligible delivery partners
        |--------------------------------------------------------------------------
        | 1. Approved
        | 2. Available
        | 3. Online
        | 4. GPS location exists
        | 5. GPS location is reasonably fresh
        |--------------------------------------------------------------------------
        */
        $profiles = DeliveryProfile::query()
            ->with('user')
            ->where('approval_status', 'approved')
            ->where('is_available', true)
            ->where('is_online', true)
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
           ->where('location_updated_at', '>=', now()->subMinutes(60))
            ->get();

        if ($profiles->isEmpty()) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Exclude drivers already offered this order
        |--------------------------------------------------------------------------
        */
        $alreadyOfferedUserIds = DeliveryOffer::query()
            ->where('order_id', $order->id)
            ->pluck('delivery_man_id');

        $profiles = $profiles
            ->reject(function ($profile) use ($alreadyOfferedUserIds) {
                return $alreadyOfferedUserIds->contains($profile->user_id);
            });

        if ($profiles->isEmpty()) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate distance from store pickup point
        |--------------------------------------------------------------------------
        */
        $profiles = $profiles
            ->map(function ($profile) use (
                $storeLatitude,
                $storeLongitude
            ) {

                $profile->calculated_distance = $this->distanceKm(
                    $storeLatitude,
                    $storeLongitude,
                    (float) $profile->current_latitude,
                    (float) $profile->current_longitude
                );

                /*
                 * Rating will be connected to the review system later.
                 * Until then, use 0 safely.
                 */
                $profile->calculated_rating = 0;

                return $profile;
            });

        /*
        |--------------------------------------------------------------------------
        | Ranking
        |--------------------------------------------------------------------------
        | Primary: nearest delivery partner
        | Secondary: better customer rating
        |--------------------------------------------------------------------------
        */
        $profiles = $profiles
            ->sort(function ($a, $b) {

                $distanceCompare =
                    $a->calculated_distance
                    <=>
                    $b->calculated_distance;

                if ($distanceCompare !== 0) {
                    return $distanceCompare;
                }

                return
                    $b->calculated_rating
                    <=>
                    $a->calculated_rating;
            })
            ->values();

        $bestProfile = $profiles->first();

        if (!$bestProfile) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Sequence number
        |--------------------------------------------------------------------------
        */
       $sequence = DeliveryOffer::query()
        ->where('order_id', $order->id)
        ->max('offer_sequence');
        
       $sequence = ($sequence ?? 0) + 1;

        /*
        |--------------------------------------------------------------------------
        | Create 30-second delivery offer
        |--------------------------------------------------------------------------
        */
        return DB::transaction(function () use (
            $order,
            $bestProfile,
            $sequence
        ) {

            return DeliveryOffer::create([
                'order_id' => $order->id,

                'delivery_man_id' =>
                    $bestProfile->user_id,

                'status' => 'pending',

                'sequence' => $sequence,

                'distance_km' =>
                    round(
                        $bestProfile->calculated_distance,
                        2
                    ),

                'rating_snapshot' =>
                    $bestProfile->calculated_rating,

                'offered_at' => now(),

                'expires_at' =>
                    now()->addSeconds(30),
            ]);
        });
    }


    /**
     * Calculate distance between two GPS coordinates.
     *
     * Uses the Haversine formula.
     */
    private function distanceKm(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {

        $earthRadius = 6371;

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);

        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a =
            sin($latDelta / 2) ** 2
            +
            cos($latFrom)
            *
            cos($latTo)
            *
            sin($lonDelta / 2) ** 2;

        $c =
            2
            *
            atan2(
                sqrt($a),
                sqrt(1 - $a)
            );

        return $earthRadius * $c;
    }
}
