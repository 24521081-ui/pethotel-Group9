<?php

namespace App\Models;

class Service extends BaseModel
{
    protected $table = 'services'; // Tên bảng có 's'
    protected $primaryKey = 'service_id';
    protected $fillable = [
        'service_id',
        'service_category_id',
        'service_name',
        'species',
        'description_sv',
        'base_price',
        'duration_minutes',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryService::class, 'service_category_id', 'service_category_id');
    }

    public function bookingServicesPet()
    {
        return $this->hasMany(BookingServicePet::class, 'service_id', 'service_id');
    }

    // [BỔ SUNG] Quan hệ 1-N: Lấy các dòng cấu hình định mức của dịch vụ này
    public function serviceStandards()
    {
        return $this->hasMany(ServiceProductStandard::class, 'service_id', 'service_id');
    }

    // [BỔ SUNG] Quan hệ N-N: Lấy trực tiếp danh sách Sản phẩm (vật tư tiêu hao) kèm số lượng định mức
    public function products()
    {
        return $this->belongsToMany(Product::class, 'service_product_standard', 'service_id', 'product_id')
            // Cập nhật đúng tên cột từ bảng service_product_standard
            ->withPivot('usage_amount', 'usage_unit', 'species', 'min_weight_kg', 'max_weight_kg');
    }
}
