<?php

namespace App\Models;

class BranchInventory extends BaseModel
{
    protected $table = 'branch_inventory';

    // Bỏ qua primary key đơn
    protected $primaryKey = null;
    public $incrementing = false;

    // Tắt timestamp vì database đang dùng tự động cập nhật SQL (ON UPDATE CURRENT_TIMESTAMP)
    public $timestamps = false;

    protected $fillable = ['branch_id', 'product_id', 'quantity_in_stock', 'reorder_point'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
