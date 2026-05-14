<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $fillable = ['order_id', 'customer_id', 'branch_id', 'booking_id', 'created_by_emp', 'status', 'subtotal', 'grand_total', 'created_at'];

    const UPDATED_AT = null;
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'created_by_emp', 'employee_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

    // Quan hệ 1-1: 1 Hóa đơn chỉ có 1 lần Thanh toán
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }
}
