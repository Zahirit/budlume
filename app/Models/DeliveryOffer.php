<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryOffer extends Model
{
    protected $fillable = [
        'order_id',
        'delivery_man_id',
        'status',
        'offer_sequence',
        'distance_km',
        'rating_snapshot',
        'offered_at',
        'expires_at',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'distance_km' => 'decimal:2',
            'rating_snapshot' => 'decimal:2',
            'offered_at' => 'datetime',
            'expires_at' => 'datetime',
            'responded_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryMan(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'delivery_man_id'
        );
    }
}