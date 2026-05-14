<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $customers = [
            ['customer_id' => 'CUS003', 'full_name' => 'Lê Quang Minh', 'email' => 'minh.lq@gmail.com', 'phone' => '0912345601', 'address' => 'Dĩ An, Bình Dương', 'created_at' => $now, 'updated_at' => $now],
            ['customer_id' => 'CUS004', 'full_name' => 'Hoàng Thanh Tùng', 'email' => 'tung.ht@outlook.com', 'phone' => '0912345602', 'address' => 'Quận 1, TP.HCM', 'created_at' => $now, 'updated_at' => $now],
            ['customer_id' => 'CUS005', 'full_name' => 'Nguyễn Mai Anh', 'email' => 'maianh.ng@gmail.com', 'phone' => '0912345603', 'address' => 'Quận 3, TP.HCM', 'created_at' => $now, 'updated_at' => $now],
            ['customer_id' => 'CUS006', 'full_name' => 'Vũ Đức Duy', 'email' => 'duy.vd@gmail.com', 'phone' => '0912345604', 'address' => 'Thủ Đức, TP.HCM', 'created_at' => $now, 'updated_at' => $now],
            ['customer_id' => 'CUS007', 'full_name' => 'Đặng Thu Thảo', 'email' => 'thao.dt@gmail.com', 'phone' => '0912345605', 'address' => 'Bình Thạnh, TP.HCM', 'created_at' => $now, 'updated_at' => $now],
            ['customer_id' => 'CUS008', 'full_name' => 'Phan Anh Tuấn', 'email' => 'tuan.pa@gmail.com', 'phone' => '0912345606', 'address' => 'Quận 7, TP.HCM', 'created_at' => $now, 'updated_at' => $now],
            ['customer_id' => 'CUS009', 'full_name' => 'Lê Thị Hồng', 'email' => 'hong.lt@gmail.com', 'phone' => '0912345607', 'address' => 'Gò Vấp, TP.HCM', 'created_at' => $now, 'updated_at' => $now],
            ['customer_id' => 'CUS010', 'full_name' => 'Bùi Xuân Huấn', 'email' => 'huan.bx@gmail.com', 'phone' => '0912345608', 'address' => 'Quận 10, TP.HCM', 'created_at' => $now, 'updated_at' => $now],
            ['customer_id' => 'CUS011', 'full_name' => 'Trần Văn Kiên', 'email' => 'kien.tv@gmail.com', 'phone' => '0912345609', 'address' => 'Quận 12, TP.HCM', 'created_at' => $now, 'updated_at' => $now],
            ['customer_id' => 'CUS012', 'full_name' => 'Ngô Bảo Châu', 'email' => 'chau.nb@gmail.com', 'phone' => '0912345610', 'address' => 'Quận 2, TP.HCM', 'created_at' => $now, 'updated_at' => $now],
        ];

        // Insert nguyên mảng vào database bằng Query Builder cho tốc độ cao nhất
        DB::table('customer')->insert($customers);
    }
}
