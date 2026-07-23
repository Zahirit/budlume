<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


#[Fillable([
    'name',
    'email',
    'phone',
    'phone_otp',
    'phone_otp_expires_at',
    'password',
    'profile_photo',
    'role',

    'email_otp',
    'email_otp_expires_at',

    'address_line_1',
    'address_line_2',
    'city',
    'state',
    'postal_code',
    'country',
])]
#[Hidden([
    'password',
    'remember_token',
    'email_otp',
    'phone_otp',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     */
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'email_otp_expires_at' => 'datetime',
        'phone_otp_expires_at' => 'datetime',
        'password' => 'hashed',
    ];
}


/**
 * Check if the user is an administrator.
 */
public function isAdmin(): bool
{
    return $this->role === 'admin';
}

/**
 * Check if the user is a customer.
 */
public function isCustomer(): bool
{
    return $this->role === 'customer';
}

/**
 * Check if the user is a delivery man.
 */
public function isDelivery(): bool
{
    return $this->role === 'delivery';
}


/**
 * Delivery profile belonging to this user.
 */
public function deliveryProfile(): HasOne
{
    return $this->hasOne(
        DeliveryProfile::class
    );
}


}