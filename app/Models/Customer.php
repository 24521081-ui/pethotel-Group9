<?php

namespace App\Models;

class Customer extends BaseModel
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = ['customer_id', 'user_id', 'full_name', 'email', 'phone', 'address', 'note'];

    // Quan hệ 1-N: 1 Khách hàng có nhiều Thú cưng
    public function pets()
    {
        return $this->hasMany(Pet::class, 'customer_id', 'customer_id');
    }

    // Quan hệ 1-N: 1 Khách hàng có nhiều Đặt phòng
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'customer_id');
    }

    // Quan hệ 1-N: 1 Khách hàng có nhiều Đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }

    // Quan hệ 1-1: Khách hàng có 1 Tài khoản đăng nhập (liên kết qua bảng users)
    public function user()
    {
        // Vì bảng users đang giữ customer_id, ta dùng hasOne
        return $this->hasOne(User::class, 'customer_id', 'customer_id');
    }
}
