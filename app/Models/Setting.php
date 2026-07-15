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
    ];
}