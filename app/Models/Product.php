<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';
    protected $fillable = ['product_id', 'product_category_id', 'product_name', 'unit', 'cost_price'];

    // Quan hệ N-1: Sản phẩm thuộc 1 Danh mục
    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'product_category_id', 'product_category_id');
    }

    // [BỔ SUNG] Quan hệ N-N: Kiểm tra số lượng tồn kho của Sản phẩm này tại các Chi nhánh
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_inventory', 'product_id', 'branch_id')
            ->withPivot('quantity_in_stock', 'reorder_point');
    }

    // [BỔ SUNG] Quan hệ 1-N: Xem Sản phẩm này đang được dùng làm định mức cho những Dịch vụ nào
    public function serviceStandards()
    {
        return $this->hasMany(ServiceProductStandard::class, 'product_id', 'product_id');
    }
}
