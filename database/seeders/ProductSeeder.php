<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $products = [
            ['product_id' => 'PROD02', 'product_category_id' => 'CATP02', 'product_name' => 'Vòng cổ da', 'unit' => 'Cái', 'cost_price' => 150000, 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 'PROD03', 'product_category_id' => 'CATP03', 'product_name' => 'Sữa tắm SOS', 'unit' => 'Chai', 'cost_price' => 120000, 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 'PROD04', 'product_category_id' => 'CATP04', 'product_name' => 'Bóng cao su', 'unit' => 'Cái', 'cost_price' => 45000, 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 'PROD05', 'product_category_id' => 'CATP01', 'product_name' => 'Pate Whiskas', 'unit' => 'Gói', 'cost_price' => 15000, 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 'PROD06', 'product_category_id' => 'CATP02', 'product_name' => 'Dây dắt tự động', 'unit' => 'Cái', 'cost_price' => 350000, 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 'PROD07', 'product_category_id' => 'CATP03', 'product_name' => 'Xịt khử mùi', 'unit' => 'Chai', 'cost_price' => 95000, 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 'PROD08', 'product_category_id' => 'CATP04', 'product_name' => 'Cần câu mèo', 'unit' => 'Cái', 'cost_price' => 35000, 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 'PROD09', 'product_category_id' => 'CATP01', 'product_name' => 'Hạt Minino', 'unit' => 'Bao', 'cost_price' => 450000, 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 'PROD10', 'product_category_id' => 'CATP05', 'product_name' => 'Thuốc trị rận', 'unit' => 'Tuýp', 'cost_price' => 180000, 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 'PROD11', 'product_category_id' => 'CATP02', 'product_name' => 'Ổ nằm bông', 'unit' => 'Cái', 'cost_price' => 550000, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('product')->insert($products);
    }
}
