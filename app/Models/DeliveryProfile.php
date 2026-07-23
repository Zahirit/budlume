<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryProfile extends Model
{
    protected $fillable = [
        'user_id',
        'approval_status',
        'approved_at',
        'approved_by',
        'is_available',
        'vehicle_type',
        'vehicle_number',
        'driving_license_number',
        'driving_license_photo',
        'sin_number',
        'notes',

    // Live delivery status
    'is_online',
    'last_seen_at',
    'current_latitude',
    'current_longitude',
    'location_updated_at',

    ];

    protected $hidden = [
        'sin_number',
    ];

    protected function casts(): array
    {
        return [
        'approved_at' => 'datetime',
        'is_available' => 'boolean',

        'is_online' => 'boolean',
        'last_seen_at' => 'datetime',
        'current_latitude' => 'decimal:7',
        'current_longitude' => 'decimal:7',
        'location_updated_at' => 'datetime',

            // Encrypt SIN automatically before storing in database
            'sin_number' => 'encrypted',
        ];
    }

    /**
     * Delivery man's user account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Admin who approved the delivery account.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}