<?php

namespace App\Models;

class OrderDetail extends BaseModel
{
    protected $table = 'order_details';
    protected $primaryKey = 'order_detail_id';
    protected $fillable = ['order_detail_id', 'booking_room_id', 'booking_service_id', 'order_id', 'note', 'quantity', 'unit_price', 'line_total', 'created_at'];

    const UPDATED_AT = null;

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function bookingRoom()
    {
        return $this->belongsTo(BookingRoom::class, 'booking_room_id', 'booking_room_id');
    }

    public function bookingServicePet()
    {
        return $this->belongsTo(BookingServicePet::class, 'booking_service_id', 'booking_service_id');
    }
}
