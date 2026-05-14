<?php

namespace App\Models;

class Payment extends BaseModel
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'payment_id',
        'order_id',
        'payment_method',
        'provider',
        'amount',
        'status',
        'paid_at',
        'note'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
