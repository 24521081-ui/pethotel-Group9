<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeoplePetSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('employee')->insert([
            [
                'employee_id' => 1,
                'user_id' => 2,
                'branch_id' => 1,
                'full_name' => 'Nguyễn Lễ Tân',
                'position' => 'RECEPTIONIST',
                'salary' => 8000000,
                'phone' => '0911000001',
                'hire_date' => '2026-01-10',
                'experience' => '1 năm',
                'notes' => 'Nhân viên lễ tân chi nhánh Quận 1.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 2,
                'user_id' => 3,
                'branch_id' => 1,
                'full_name' => 'Trần Groomer',
                'position' => 'GROOMER',
                'salary' => 9000000,
                'phone' => '0911000002',
                'hire_date' => '2026-01-15',
                'experience' => '2 năm',
                'notes' => 'Phụ trách grooming.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 3,
                'user_id' => 4,
                'branch_id' => 2,
                'full_name' => 'Lê Quản Lý',
                'position' => 'MANAGER',
                'salary' => 15000000,
                'phone' => '0911000003',
                'hire_date' => '2026-01-20',
                'experience' => '3 năm',
                'notes' => 'Quản lý chi nhánh Thủ Đức.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('customer')->insert([
            [
                'customer_id' => 1,
                'user_id' => 5,
                'full_name' => 'Nguyễn Văn A',
                'phone' => '0922000001',
                'address' => 'Quận Bình Thạnh, TP.HCM',
                'notes' => 'Khách hàng thân thiết.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'customer_id' => 2,
                'user_id' => 6,
                'full_name' => 'Trần Thị B',
                'phone' => '0922000002',
                'address' => 'TP. Thủ Đức, TP.HCM',
                'notes' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('pet')->insert([
            [
                'pet_id' => 1,
                'customer_id' => 1,
                'pet_name' => 'Milo',
                'species' => 'DOG',
                'breed' => 'Poodle',
                'age' => 3,
                'weight_kg' => 5.50,
                'special_notes' => 'Hiền, hơi sợ máy sấy.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'pet_id' => 2,
                'customer_id' => 1,
                'pet_name' => 'Miu',
                'species' => 'CAT',
                'breed' => 'Anh lông ngắn',
                'age' => 2,
                'weight_kg' => 4.20,
                'special_notes' => 'Không thích ở gần chó lớn.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'pet_id' => 3,
                'customer_id' => 2,
                'pet_name' => 'Ben',
                'species' => 'DOG',
                'breed' => 'Corgi',
                'age' => 4,
                'weight_kg' => 14.00,
                'special_notes' => 'Ăn theo khẩu phần riêng.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
