<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Lưu ý:
         * - Trigger trg_audit_booking_insert có thể đã tự tạo log khi BookingSeeder insert booking.
         * - Seeder này bổ sung thêm log mẫu thủ công để bảng audit_log chắc chắn có dữ liệu.
         * - Không set audit_id để tránh trùng với log do trigger sinh ra.
         */
        DB::table('audit_log')->insert([
            [
                'table_name' => 'users',
                'action_type' => 'UPDATE',
                'row_pk' => '4',
                'detail_text' => 'Demo audit: manager account reviewed by seeder.',
                'changed_by_user_id' => 1,
                'changed_at' => now(),
            ],
            [
                'table_name' => 'orders',
                'action_type' => 'INSERT',
                'row_pk' => '1',
                'detail_text' => 'Demo audit: order sample created during seeding.',
                'changed_by_user_id' => 2,
                'changed_at' => now(),
            ],
        ]);
    }
}
