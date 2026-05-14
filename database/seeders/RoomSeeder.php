<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bảng room chỉ có created_at, tự động lấy giờ hiện tại
        $now = now();

        $rooms = [
            ['room_id' => 'RM102', 'branch_id' => 'BR003', 'type_room_id' => 'TYPE02', 'room_number' => 'S102', 'status' => 'AVAILABLE', 'created_at' => $now],
            ['room_id' => 'RM103', 'branch_id' => 'BR005', 'type_room_id' => 'TYPE03', 'room_number' => 'VIP1', 'status' => 'IN_USE', 'created_at' => $now],
            ['room_id' => 'RM201', 'branch_id' => 'BR004', 'type_room_id' => 'TYPE01', 'room_number' => 'P201', 'status' => 'AVAILABLE', 'created_at' => $now],
            ['room_id' => 'RM202', 'branch_id' => 'BR003', 'type_room_id' => 'TYPE02', 'room_number' => 'S202', 'status' => 'MAINTENANCE', 'created_at' => $now],
            ['room_id' => 'RM301', 'branch_id' => 'BR003', 'type_room_id' => 'TYPE01', 'room_number' => 'P301', 'status' => 'AVAILABLE', 'created_at' => $now],
            ['room_id' => 'RM302', 'branch_id' => 'BR003', 'type_room_id' => 'TYPE03', 'room_number' => 'VIP3', 'status' => 'AVAILABLE', 'created_at' => $now],
            ['room_id' => 'RM401', 'branch_id' => 'BR004', 'type_room_id' => 'TYPE01', 'room_number' => 'P401', 'status' => 'AVAILABLE', 'created_at' => $now],
            ['room_id' => 'RM402', 'branch_id' => 'BR004', 'type_room_id' => 'TYPE02', 'room_number' => 'S402', 'status' => 'IN_USE', 'created_at' => $now],
            ['room_id' => 'RM501', 'branch_id' => 'BR005', 'type_room_id' => 'TYPE01', 'room_number' => 'P501', 'status' => 'AVAILABLE', 'created_at' => $now],
            ['room_id' => 'RM502', 'branch_id' => 'BR005', 'type_room_id' => 'TYPE03', 'room_number' => 'VIP5', 'status' => 'AVAILABLE', 'created_at' => $now],
        ];

        DB::table('room')->insert($rooms);
    }
}
