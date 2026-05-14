<?php

namespace App\Models;

class BookingRoom extends BaseModel
{
    protected $table = 'booking_room'; // Chú ý hoa thường theo Migration của bạn
    protected $primaryKey = 'booking_room_id';

    // Bảng này SQL chỉ có assigned_at, không có updated_at
    public $timestamps = false;

    protected $fillable = ['booking_room_id', 'booking_id', 'room_id', 'assigned_at', 'note'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'booking_room_pet', 'booking_room_id', 'pet_id')
            ->withPivot('assigned_at', 'note');
    }
}
