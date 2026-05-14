<?php

namespace App\Models;

class CategoryService extends BaseModel
{
    protected $table = 'category_services';
    protected $primaryKey = 'service_category_id';
    protected $fillable = ['service_category_id', 'category_name', 'note'];

    public function services()
    {
        return $this->hasMany(Service::class, 'service_category_id', 'service_category_id');
    }
}
