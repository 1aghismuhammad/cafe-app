<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = [
        'outlet_name',
        'outlet_code',
        'address',
        'city',
        'province',
        'phone_number',
        'email',
        'open_time',
        'close_time',
        'status',
    ];

    public function restaurantTables()
    {
        return $this->hasMany(RestaurantTable::class);
    }
}