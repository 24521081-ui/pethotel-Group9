<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanupSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            /*
             * Xóa dữ liệu theo thứ tự ngược nghiệp vụ.
             * Có bao gồm cả bảng hệ thống Laravel để khi chạy seed lại không bị trùng dữ liệu.
             */
            $tables = [
                // Bảng nghiệp vụ cuối luồng
                'booking_coupon_log',
                'order_details',
                'orders',
                'booking_service_pet',
                'booking_room_pet',
                'booking_room',
                'booking',
                'branch_inventory',
                'coupon',
                'pet',
                'customer',
                'employee',
                'service_product_detail',
                'services',
                'product',
                'category_services',
                'category_product',
                'room',
                'type_room',
                'branch',
                'audit_log',

                // Bảng người dùng và auth support
                'sessions',
                'password_reset_tokens',
                'users',

                // Bảng hệ thống Laravel
                'failed_jobs',
                'job_batches',
                'jobs',
                'cache_locks',
                'cache',
            ];

            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
