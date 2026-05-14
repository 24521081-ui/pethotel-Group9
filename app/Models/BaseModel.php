<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    // Tắt tự động tăng ID
    public $incrementing = false;
    // Báo cho Laravel biết khóa chính là chuỗi
    protected $keyType = 'string';
}
