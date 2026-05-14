<?php

namespace App\Models;

class ServiceProductStandard extends BaseModel
{
    protected $table = 'service_product_standard';
    protected $primaryKey = 'standard_id';
    protected $fillable = [
        'standard_id',
        'service_id',
        'product_id',
        'species',
        'min_weight_kg',
        'max_weight_kg',
        'usage_amount',
        'usage_unit',
        'note'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
