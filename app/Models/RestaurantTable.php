<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $fillable = [
        'outlet_id',
        'table_number',
        'table_code',
        'capacity',
        'status',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}