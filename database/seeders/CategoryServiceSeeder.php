<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $categories = [
            ['service_category_id' => 'CATS01', 'category_name' => 'Lấy cao răng', 'created_at' => $now, 'updated_at' => $now],
            ['service_category_id' => 'CATS02', 'category_name' => 'Cắt tỉa', 'created_at' => $now, 'updated_at' => $now],
            ['service_category_id' => 'CATS03', 'category_name' => 'Khách sạn', 'created_at' => $now, 'updated_at' => $now],
            ['service_category_id' => 'CATS04', 'category_name' => 'Trị liệu', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('category_services')->insert($categories);
    }
}
