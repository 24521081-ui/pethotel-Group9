<?php

namespace App\Models;

class Booking extends BaseModel
{
    protected $table = 'booking';
    protected $primaryKey = 'booking_id';
    protected $fillable = [
        'booking_id',
        'customer_id',
        'branch_id',
        'checkin_expected_at',
        'checkout_expected_at',
        'status',
        'deposit_amount',
        'special_note'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    // [ĐÃ SỬA] Quan hệ N-N lấy danh sách Phòng của Booking này
    public function rooms()
    {
        // Sử dụng đúng bảng trung gian 'booking_room'
        return $this->belongsToMany(Room::class, 'booking_room', 'booking_id', 'room_id')
            // Lấy thêm các cột phụ trong bảng booking_room nếu bạn cần dùng
            ->withPivot('booking_room_id', 'assigned_at', 'note');
    }

    // [BỔ SUNG THÊM] Hàm này rất hữu ích nếu bạn muốn lấy trực tiếp cục BookingRoom để truy vấn tiếp ra Pet
    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class, 'booking_id', 'booking_id');
    }

    public function bookingServicesPet()
    {
        return $this->hasMany(BookingServicePet::class, 'booking_id', 'booking_id');
    }
}
