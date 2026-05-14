<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $employees = [
            ['employee_id' => 'EMP001', 'branch_id' => 'BR003', 'full_name' => 'Nguyễn Thị Thắm', 'salary' => 12000000, 'phone' => '0981000001', 'status_code' => 'WORKING', 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 'EMP002', 'branch_id' => 'BR004', 'full_name' => 'Trần Văn Hùng', 'salary' => 13000000, 'phone' => '0981000002', 'status_code' => 'WORKING', 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 'EMP003', 'branch_id' => 'BR003', 'full_name' => 'Lê Mỹ Linh', 'salary' => 14500000, 'phone' => '0981000003', 'status_code' => 'WORKING', 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 'EMP004', 'branch_id' => 'BR004', 'full_name' => 'Hoàng Phi Hùng', 'salary' => 12500000, 'phone' => '0981000004', 'status_code' => 'ON_LEAVE', 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 'EMP005', 'branch_id' => 'BR005', 'full_name' => 'Đặng Nam Anh', 'salary' => 16000000, 'phone' => '0981000005', 'status_code' => 'WORKING', 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 'EMP006', 'branch_id' => 'BR005', 'full_name' => 'Vũ Tuyết Mai', 'salary' => 11000000, 'phone' => '0981000006', 'status_code' => 'RESIGNED', 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 'EMP007', 'branch_id' => 'BR003', 'full_name' => 'Lý Gia Thành', 'salary' => 20000000, 'phone' => '0981000007', 'status_code' => 'WORKING', 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 'EMP008', 'branch_id' => 'BR003', 'full_name' => 'Trương Vô Kỵ', 'salary' => 18000000, 'phone' => '0981000008', 'status_code' => 'WORKING', 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 'EMP009', 'branch_id' => 'BR004', 'full_name' => 'Triệu Mẫn', 'salary' => 17000000, 'phone' => '0981000009', 'status_code' => 'WORKING', 'created_at' => $now, 'updated_at' => $now],
            ['employee_id' => 'EMP010', 'branch_id' => 'BR005', 'full_name' => 'Chu Chỉ Nhược', 'salary' => 15500000, 'phone' => '0981000010', 'status_code' => 'WORKING', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('employee')->insert($employees);
    }
}
