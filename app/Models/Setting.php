<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'logo',
        'phone',
        'email',
        'address',
        'footer_text',
        'facebook',
        'instagram',
        'twitter',
        'store_latitude',
        'store_longitude',
    ];

    protected function casts(): array
    {
        return [
            'store_latitude' => 'decimal:7',
            'store_longitude' => 'decimal:7',
        ];
    }
}