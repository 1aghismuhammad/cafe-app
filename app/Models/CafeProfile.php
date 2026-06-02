<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CafeProfile extends Model
{
    protected $fillable = [
        'brand_name',
        'legal_name',
        'slogan',
        'description',
        'logo_path',
        'primary_color',
        'secondary_color',
        'accent_color',
        'whatsapp_number',
        'instagram_url',
        'tiktok_url',
        'email',
        'google_maps_url',
    ];
}