<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductServiceSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('category_product')->insert([
            [
                'product_category_id' => 1,
                'product_category_name' => 'Sữa tắm',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_category_id' => 2,
                'product_category_name' => 'Thức ăn',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_category_id' => 3,
                'product_category_name' => 'Vật tư chăm sóc',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('product')->insert([
            [
                'product_id' => 1,
                'product_category_id' => 1,
                'product_image_url' => 'products/shampoo-dog-cat.jpg',
                'product_name' => 'Sữa tắm chó mèo dịu nhẹ',
                'unit' => 'ml',
                'item_price' => 1200,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 2,
                'product_category_id' => 2,
                'product_image_url' => 'products/pet-food.jpg',
                'product_name' => 'Thức ăn hạt tổng hợp',
                'unit' => 'gram',
                'item_price' => 300,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 3,
                'product_category_id' => 3,
                'product_image_url' => 'products/towel.jpg',
                'product_name' => 'Khăn lau thú cưng',
                'unit' => 'cái',
                'item_price' => 15000,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('category_services')->insert([
            [
                'service_category_id' => 1,
                'service_category_name' => 'Lưu trú',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_category_id' => 2,
                'service_category_name' => 'Grooming',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_category_id' => 3,
                'service_category_name' => 'Chăm sóc sức khỏe',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('services')->insert([
            [
                'service_id' => 1,
                'service_category_id' => 2,
                'service_name' => 'Tắm sấy cơ bản',
                'species' => 'ALL',
                'description_sv' => 'Dịch vụ tắm sấy cơ bản cho chó mèo.',
                'base_price' => 120000,
                'duration_minutes' => 60,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_id' => 2,
                'service_category_id' => 2,
                'service_name' => 'Cắt tỉa lông',
                'species' => 'DOG',
                'description_sv' => 'Dịch vụ cắt tỉa lông cho chó.',
                'base_price' => 180000,
                'duration_minutes' => 90,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_id' => 3,
                'service_category_id' => 3,
                'service_name' => 'Kiểm tra sức khỏe cơ bản',
                'species' => 'ALL',
                'description_sv' => 'Kiểm tra sức khỏe nhanh trước khi lưu trú.',
                'base_price' => 80000,
                'duration_minutes' => 30,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('service_product_detail')->insert([
            [
                'service_product_detail_id' => 1,
                'service_id' => 1,
                'product_id' => 1,
                'amount' => 50.00,
                'notes' => 'Sữa tắm dùng cho một lần tắm sấy.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_product_detail_id' => 2,
                'service_id' => 1,
                'product_id' => 3,
                'amount' => 1.00,
                'notes' => 'Khăn lau dùng khi tắm sấy.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'service_product_detail_id' => 3,
                'service_id' => 3,
                'product_id' => 2,
                'amount' => 100.00,
                'notes' => 'Thức ăn mẫu sau khi kiểm tra.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
