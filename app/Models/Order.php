<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [

        // Customer relationship
        'customer_id',

        // Guest or registered
        'customer_type',

        // Order information
        'order_number',

        // Customer snapshot
        'customer_name',
        'customer_email',
        'customer_phone',

        // Delivery address snapshot
        'delivery_address_line_1',
        'delivery_address_line_2',
        'delivery_city',
        'delivery_state',
        'delivery_postal_code',
        'delivery_country',

        // Pricing
        'subtotal',
        'discount_percentage',
        'discount_amount',
        'total_amount',

        // Mobile verification
        'phone_verified_at',

        // Secure tracking
        'tracking_token',

        // Order status
        'status',
        'notes',
    ];

    /**
     * Cast database values.
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'phone_verified_at' => 'datetime',
        ];
    }

    /**
     * Customer related to this order.
     *
     * Guest orders may have customer_id = NULL.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Items belonging to this order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}