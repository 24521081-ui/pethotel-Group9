<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchRoomSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('branch')->insert([
    [
        'branch_id' => 1,
        'branch_name' => 'Pet Hotel Quận 1',
        'phone' => '0901000001',
        'email' => 'q1@pethotel.test',
        'address' => '123 Nguyễn Huệ, Quận 1, TP.HCM',
        'is_active' => true,
        'created_at' => $now,
        'updated_at' => $now,
    ],
    [
        'branch_id' => 2,
        'branch_name' => 'Pet Hotel Thủ Đức',
        'phone' => '0901000002',
        'email' => 'thuduc@pethotel.test',
        'address' => '456 Võ Văn Ngân, TP. Thủ Đức, TP.HCM',
        'is_active' => true,
        'created_at' => $now,
        'updated_at' => $now,
    ],
    [
        'branch_id' => 3,
        'branch_name' => 'Pet Hotel Quận 7',
        'phone' => '0901000003',
        'email' => 'q7@pethotel.test',
        'address' => '789 Nguyễn Thị Thập, Quận 7, TP.HCM',
        'is_active' => true,
        'created_at' => $now,
        'updated_at' => $now,
    ],
    [
        'branch_id' => 4,
        'branch_name' => 'Pet Hotel Gò Vấp',
        'phone' => '0901000004',
        'email' => 'govap@pethotel.test',
        'address' => '321 Phan Văn Trị, Gò Vấp, TP.HCM',
        'is_active' => true,
        'created_at' => $now,
        'updated_at' => $now,
    ],
]);

        DB::table('type_room')->insert([
            [
                'type_room_id' => 1,
                'type_name' => 'Phòng nhỏ',
                'max_slot' => 2,
                'pet_weight_min_kg' => 0.00,
                'pet_weight_max_kg' => 10.00,
                'base_price_per_day' => 150000,
                'notes' => 'Phù hợp chó/mèo nhỏ dưới 10kg.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type_room_id' => 2,
                'type_name' => 'Phòng vừa',
                'max_slot' => 2,
                'pet_weight_min_kg' => 10.00,
                'pet_weight_max_kg' => 25.00,
                'base_price_per_day' => 220000,
                'notes' => 'Phù hợp thú cưng từ 10kg đến 25kg.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type_room_id' => 3,
                'type_name' => 'Phòng lớn',
                'max_slot' => 1,
                'pet_weight_min_kg' => 25.00,
                'pet_weight_max_kg' => 45.00,
                'base_price_per_day' => 350000,
                'notes' => 'Phù hợp thú cưng lớn, mỗi phòng 1 bé.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('room')->insert([
            [
                'room_id' => 1,
                'branch_id' => 1,
                'type_room_id' => 1,
                'room_number' => 'Q1-S01',
                'status' => 'AVAILABLE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'room_id' => 2,
                'branch_id' => 1,
                'type_room_id' => 2,
                'room_number' => 'Q1-M01',
                'status' => 'AVAILABLE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'room_id' => 3,
                'branch_id' => 2,
                'type_room_id' => 1,
                'room_number' => 'TD-S01',
                'status' => 'AVAILABLE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'room_id' => 4,
                'branch_id' => 2,
                'type_room_id' => 3,
                'room_number' => 'TD-L01',
                'status' => 'MAINTENANCE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
    'room_id' => 5,
    'branch_id' => 3,
    'type_room_id' => 1,
    'room_number' => 'Q7-S01',
    'status' => 'AVAILABLE',
    'created_at' => $now,
    'updated_at' => $now,
],
[
    'room_id' => 6,
    'branch_id' => 3,
    'type_room_id' => 2,
    'room_number' => 'Q7-M01',
    'status' => 'AVAILABLE',
    'created_at' => $now,
    'updated_at' => $now,
],
[
    'room_id' => 7,
    'branch_id' => 4,
    'type_room_id' => 1,
    'room_number' => 'GV-S01',
    'status' => 'AVAILABLE',
    'created_at' => $now,
    'updated_at' => $now,
],
[
    'room_id' => 8,
    'branch_id' => 4,
    'type_room_id' => 3,
    'room_number' => 'GV-L01',
    'status' => 'AVAILABLE',
    'created_at' => $now,
    'updated_at' => $now,
],
        ]);
    }
}