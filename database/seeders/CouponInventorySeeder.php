<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponInventorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('coupon')->insert([
            [
                'coupon_id' => 1,
                'coupon_code' => 'WELCOME50',
                'employee_id' => 1,
                'discount_type' => 'FIXED',
                'discount_value' => 50000,
                'max_discount' => 50000,
                'min_order_value' => 200000,
                'max_uses' => 100,
                'used_count' => 1,
                'effective_from' => $now->copy()->subDays(1),
                'expired_at' => $now->copy()->addMonths(2),
                'is_active' => true,
                'notes' => 'Giảm 50.000đ cho đơn từ 200.000đ.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'coupon_id' => 2,
                'coupon_code' => 'PET10',
                'employee_id' => 3,
                'discount_type' => 'PERCENT',
                'discount_value' => 10,
                'max_discount' => 100000,
                'min_order_value' => 300000,
                'max_uses' => 50,
                'used_count' => 0,
                'effective_from' => $now->copy()->subDays(1),
                'expired_at' => $now->copy()->addMonths(1),
                'is_active' => true,
                'notes' => 'Giảm 10%, tối đa 100.000đ.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('branch_inventory')->insert([
            [
                'branch_inventory_id' => 1,
                'branch_id' => 1,
                'product_id' => 1,
                'quantity_in_stock' => 5000,
                'reorder_point' => 1000,
                'last_updated' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'branch_inventory_id' => 2,
                'branch_id' => 1,
                'product_id' => 2,
                'quantity_in_stock' => 20000,
                'reorder_point' => 5000,
                'last_updated' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'branch_inventory_id' => 3,
                'branch_id' => 1,
                'product_id' => 3,
                'quantity_in_stock' => 100,
                'reorder_point' => 20,
                'last_updated' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'branch_inventory_id' => 4,
                'branch_id' => 2,
                'product_id' => 1,
                'quantity_in_stock' => 4000,
                'reorder_point' => 1000,
                'last_updated' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
