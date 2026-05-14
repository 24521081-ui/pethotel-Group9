<?php

namespace App\Models;

class Pet extends BaseModel
{
    protected $table = 'pet';
    protected $primaryKey = 'pet_id';
    protected $fillable = ['pet_id', 'customer_id', 'pet_name', 'species', 'breed', 'sex', 'weight_kg', 'special_note'];

    // Quan hệ N-1: Thú cưng thuộc về 1 Khách hàng (CHÍNH XÁC)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // [ĐÃ SỬA] Quan hệ N-N: Lấy danh sách các "Phòng đã đặt" (BookingRoom) mà bé Pet này từng ở
    public function bookingRooms()
    {
        // Phải dùng khóa booking_room_id vì bảng booking_room_pet cấu tạo từ pet_id và booking_room_id
        return $this->belongsToMany(BookingRoom::class, 'booking_room_pet', 'pet_id', 'booking_room_id')
            ->withPivot('assigned_at', 'note');
    }

    // [ĐÃ CHUẨN HOÁ] Quan hệ N-N: Thú cưng sử dụng nhiều Dịch vụ
    public function services()
    {
        // Lấy đúng các cột phụ có trong bảng booking_services_pet
        return $this->belongsToMany(Service::class, 'booking_services_pet', 'pet_id', 'service_id')
            ->withPivot('booking_service_id', 'booking_id', 'employee_id', 'scheduled_at', 'status', 'note');
    }

    // [BỔ SUNG THÊM] Rất cần thiết: Quan hệ 1-N với Sổ theo dõi sức khỏe
    public function healthRecords()
    {
        return $this->hasMany(PetHealthRecord::class, 'pet_id', 'pet_id');
    }
}
