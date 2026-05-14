<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Chỉ định tên khóa chính (Vì mặc định Laravel tìm cột 'id')
    protected $primaryKey = 'user_id';

    // 2. Tắt tính năng tự động tăng (Vì chúng ta dùng mã chuỗi như USR001)
    public $incrementing = false;

    // 3. Khai báo kiểu dữ liệu của khóa chính là chuỗi
    protected $keyType = 'string';

    /**
     * Các thuộc tính được phép insert/update (Mass Assignable)
     */
    protected $fillable = [
        'user_id',
        'employee_id',
        'customer_id',
        'username',
        'password',
        'role_emp',
        'is_active',
        'last_login',
    ];

    /**
     * Các thuộc tính bị ẩn đi (không hiển thị khi trả về JSON/API)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Ép kiểu dữ liệu tự động
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Tự động mã hóa mật khẩu khi lưu
            'last_login' => 'datetime',
        ];
    }
}
