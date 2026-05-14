<?php

namespace App\Models;

class BookingRoomPet extends BaseModel
{
    protected $table = 'booking_room_pet';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['booking_room_id', 'pet_id', 'assigned_at', 'note'];

    public function bookingRoom()
    {
        return $this->belongsTo(BookingRoom::class, 'booking_room_id', 'booking_room_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }
}
