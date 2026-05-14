<?php

namespace App\Models;

class Branch extends BaseModel
{
    protected $table = 'branch';
    protected $primaryKey = 'branch_id';
    protected $fillable = ['branch_id', 'branch_name', 'phone', 'email', 'address', 'is_active'];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'branch_id', 'branch_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'branch_id', 'branch_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'branch_id', 'branch_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'branch_id', 'branch_id');
    }

    // [BỔ SUNG THÊM] Quan hệ N-N để lấy danh sách sản phẩm tồn kho của Chi nhánh
    public function products()
    {
        return $this->belongsToMany(Product::class, 'branch_inventory', 'branch_id', 'product_id')
            ->withPivot('quantity_in_stock', 'reorder_point', 'last_updated');
    }
}
