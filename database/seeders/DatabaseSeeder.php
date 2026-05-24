<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CleanupSeeder::class,

            /*
             * 1. Bảng hệ thống Laravel độc lập
             * cache, cache_locks, jobs, job_batches, failed_jobs
             */
            LaravelSystemSeeder::class,

            /*
             * 2. Người dùng nền tảng
             * users
             */
            UserSeeder::class,

            /*
             * 3. Bảng auth phụ thuộc users
             * password_reset_tokens, sessions
             */
            AuthSupportSeeder::class,

            /*
             * 4. Chi nhánh, loại phòng, phòng
             * branch, type_room, room
             */
            BranchRoomSeeder::class,

            /*
             * 5. Sản phẩm, dịch vụ, định mức sản phẩm dịch vụ
             * category_product, product,
             * category_services, services, service_product_detail
             */
            ProductServiceSeeder::class,

            /*
             * 6. Nhân viên, khách hàng, thú cưng
             * employee, customer, pet
             */
            PeoplePetSeeder::class,

            /*
             * 7. Coupon và tồn kho chi nhánh
             * coupon, branch_inventory
             */
            CouponInventorySeeder::class,

            /*
             * 8. Booking và các bảng chi tiết booking
             * booking, booking_room, booking_room_pet, booking_service_pet
             */
            BookingSeeder::class,

            /*
             * 9. Đơn hàng, chi tiết đơn hàng, log coupon
             * orders, order_details, booking_coupon_log
             */
            OrderSeeder::class,

            /*
             * 10. Audit log bổ sung
             * audit_log
             */
            AuditLogSeeder::class,
        ]);
    }
}
