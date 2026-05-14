<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_details')->insert([
            ['order_detail_id' => 'OD001', 'booking_room_id' => 'BKR001', 'booking_service_id' => null, 'order_id' => 'ORD001', 'note' => 'Tiền phòng Standard', 'quantity' => 1, 'unit_price' => 200000, 'line_total' => 200000, 'created_at' => now()],
            ['order_detail_id' => 'OD002', 'booking_room_id' => null, 'booking_service_id' => 'BSP001', 'order_id' => 'ORD001', 'note' => 'Tiền dịch vụ cắt tỉa', 'quantity' => 1, 'unit_price' => 350000, 'line_total' => 350000, 'created_at' => now()],
        ]);
    }
}
