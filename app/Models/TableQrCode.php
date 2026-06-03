<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableQrCode extends Model
{
    protected $fillable = [
        'restaurant_table_id',
        'qr_token',
        'qr_url',
        'is_active',
    ];

    public function restaurantTable()
    {
        return $this->belongsTo(RestaurantTable::class);
    }
}