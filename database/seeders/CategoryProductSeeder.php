<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $categories = [
            ['product_category_id' => 'CATP01', 'category_name' => 'Thức ăn', 'created_at' => $now, 'updated_at' => $now],
            ['product_category_id' => 'CATP02', 'category_name' => 'Phụ kiện', 'created_at' => $now, 'updated_at' => $now],
            ['product_category_id' => 'CATP03', 'category_name' => 'Sữa tắm', 'created_at' => $now, 'updated_at' => $now],
            ['product_category_id' => 'CATP04', 'category_name' => 'Đồ chơi', 'created_at' => $now, 'updated_at' => $now],
            ['product_category_id' => 'CATP05', 'category_name' => 'Thú y', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('category_product')->insert($categories);
    }
}
