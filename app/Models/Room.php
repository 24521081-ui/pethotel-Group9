<?php

namespace App\Models;

class Room extends BaseModel
{
    protected $table = 'room';
    protected $primaryKey = 'room_id';
    protected $fillable = ['room_id', 'branch_id', 'type_room_id', 'room_number', 'status'];

    // Tắt updated_at để tránh lỗi SQL
    const UPDATED_AT = null;

    public function typeRoom()
    {
        return $this->belongsTo(TypeRoom::class, 'type_room_id', 'type_room_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    // [ĐÃ SỬA] Quan hệ N-N: Xem phòng này đã được book trong những đơn Booking nào
    public function bookings()
    {
        // Sử dụng đúng bảng trung gian 'booking_room'
        return $this->belongsToMany(Booking::class, 'booking_room', 'room_id', 'booking_id')
            ->withPivot('booking_room_id', 'assigned_at', 'note');
    }

    // [BỔ SUNG] Lấy trực tiếp danh sách chi tiết đặt phòng của phòng này
    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class, 'room_id', 'room_id');
    }
}
