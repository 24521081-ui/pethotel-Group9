<?php

namespace App\Models;

class Employee extends BaseModel
{
    protected $table = 'employee';
    protected $primaryKey = 'employee_id';
    protected $fillable = ['employee_id', 'user_id', 'branch_id', 'full_name', 'salary', 'email', 'phone', 'hire_date', 'status_code', 'note'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by_emp', 'employee_id');
    }

    // Quan hệ 1-1: Nhân viên có 1 Tài khoản đăng nhập
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'employee_id');
    }

    // Quan hệ 1-N: Nhân viên thực hiện nhiều Dịch vụ cho Thú cưng
    public function bookingServicesPet()
    {
        return $this->hasMany(BookingServicePet::class, 'employee_id', 'employee_id');
    }
}
