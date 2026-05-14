<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        // Set chung 1 mật khẩu là '123456' để test cho dễ
        $defaultPassword = Hash::make('123456');

        $users = [
            // THÊM 'customer_id' => null CHO TẤT CẢ NHÂN VIÊN
            ['user_id' => 'USR001', 'username' => 'nhanvien1', 'password' => $defaultPassword, 'role_emp' => '1', 'is_active' => 1, 'employee_id' => 'EMP001', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 'USR002', 'username' => 'quanly1',   'password' => $defaultPassword, 'role_emp' => '4', 'is_active' => 1, 'employee_id' => 'EMP002', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 'USR003', 'username' => 'tham.nt',   'password' => $defaultPassword, 'role_emp' => '2', 'is_active' => 1, 'employee_id' => 'EMP003', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 'USR004', 'username' => 'hung.tv',   'password' => $defaultPassword, 'role_emp' => '1', 'is_active' => 1, 'employee_id' => 'EMP004', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 'USR005', 'username' => 'linh.lm',   'password' => $defaultPassword, 'role_emp' => '2', 'is_active' => 1, 'employee_id' => 'EMP005', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 'USR006', 'username' => 'hung.hp',   'password' => $defaultPassword, 'role_emp' => '3', 'is_active' => 1, 'employee_id' => 'EMP006', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 'USR007', 'username' => 'anh.dn',    'password' => $defaultPassword, 'role_emp' => '4', 'is_active' => 1, 'employee_id' => 'EMP007', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 'USR008', 'username' => 'thanh.lg',  'password' => $defaultPassword, 'role_emp' => '5', 'is_active' => 1, 'employee_id' => 'EMP008', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 'USR009', 'username' => 'ky.tv',     'password' => $defaultPassword, 'role_emp' => '2', 'is_active' => 1, 'employee_id' => 'EMP009', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 'USR010', 'username' => 'man.t',     'password' => $defaultPassword, 'role_emp' => '2', 'is_active' => 1, 'employee_id' => 'EMP010', 'customer_id' => null, 'created_at' => $now, 'updated_at' => $now],

            // KHÁCH HÀNG: employee_id = null
            ['user_id' => 'USR011', 'username' => 'kh_quangminh', 'password' => $defaultPassword, 'role_emp' => '0', 'is_active' => 1, 'employee_id' => null, 'customer_id' => 'CUS003', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('users')->insert($users);
    }
}
