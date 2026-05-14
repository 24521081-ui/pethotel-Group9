<?php

namespace App\Models;

class CategoryProduct extends BaseModel
{
    protected $table = 'category_product';
    protected $primaryKey = 'product_category_id';
    protected $fillable = ['product_category_id', 'category_name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id', 'product_category_id');
    }
}
