<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'table_qr_code_id',
        'restaurant_table_id',
        'outlet_id',
        'customer_name',
        'customer_phone',
        'customer_note',
        'total_amount',
        'status',
        'payment_status',
    ];

    public function tableQrCode()
    {
        return $this->belongsTo(TableQrCode::class);
    }

    public function restaurantTable()
    {
        return $this->belongsTo(RestaurantTable::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}