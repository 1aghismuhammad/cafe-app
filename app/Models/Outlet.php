<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * The users assigned to this outlet.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_outlet')
            ->withTimestamps();
    }
}