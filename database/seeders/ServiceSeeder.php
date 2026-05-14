<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $services = [
            ['service_id' => 'SER002', 'service_category_id' => 'CATS02', 'service_name' => 'Cắt tỉa tạo kiểu', 'species' => 'DOG', 'base_price' => 350000, 'duration_minutes' => 90, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_id' => 'SER003', 'service_category_id' => 'CATS02', 'service_name' => 'Cạo lông máu', 'species' => 'CAT', 'base_price' => 250000, 'duration_minutes' => 45, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_id' => 'SER004', 'service_category_id' => 'CATS01', 'service_name' => 'Lấy cao răng', 'species' => 'DOG', 'base_price' => 150000, 'duration_minutes' => 30, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_id' => 'SER005', 'service_category_id' => 'CATS04', 'service_name' => 'Massage thư giãn', 'species' => 'DOG', 'base_price' => 400000, 'duration_minutes' => 60, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_id' => 'SER006', 'service_category_id' => 'CATS01', 'service_name' => 'Cắt móng mài móng', 'species' => 'CAT', 'base_price' => 80000, 'duration_minutes' => 20, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_id' => 'SER007', 'service_category_id' => 'CATS02', 'service_name' => 'Nhuộm lông tai', 'species' => 'DOG', 'base_price' => 200000, 'duration_minutes' => 60, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_id' => 'SER008', 'service_category_id' => 'CATS04', 'service_name' => 'Xông hơi thảo dược', 'species' => 'DOG', 'base_price' => 500000, 'duration_minutes' => 45, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_id' => 'SER009', 'service_category_id' => 'CATS01', 'service_name' => 'Vệ sinh tai', 'species' => 'CAT', 'base_price' => 50000, 'duration_minutes' => 15, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_id' => 'SER010', 'service_category_id' => 'CATS01', 'service_name' => 'Tắm khô', 'species' => 'DOG', 'base_price' => 120000, 'duration_minutes' => 30, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_id' => 'SER011', 'service_category_id' => 'CATS02', 'service_name' => 'Combo Toàn diện', 'species' => 'DOG', 'base_price' => 600000, 'duration_minutes' => 120, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('services')->insert($services);
    }
}
