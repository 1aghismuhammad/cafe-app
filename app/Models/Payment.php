<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'midtrans_order_id',
        'transaction_id',
        'payment_type',
        'gross_amount',
        'currency',
        'transaction_status',
        'fraud_status',
        'qr_url',
        'raw_response',
        'raw_notification',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'raw_notification' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}