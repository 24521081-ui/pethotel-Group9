<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $typeRooms = [
            [
                'type_room_id' => 'TYPE01',
                'type_name' => 'PREMIUM',
                'note' => 'Phòng cao cấp, không gian thoáng',
                'max_pets' => 1,
                'max_weight_kg' => 15.00,
                'base_price_per_day' => 450000,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'type_room_id' => 'TYPE02',
                'type_name' => 'STANDARD',
                'note' => 'Phòng tiêu chuẩn cơ bản',
                'max_pets' => 1,
                'max_weight_kg' => 10.00,
                'base_price_per_day' => 200000,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'type_room_id' => 'TYPE03',
                'type_name' => 'SUITE',
                'note' => 'Phòng VIP siêu rộng cho gia đình thú cưng',
                'max_pets' => 2,
                'max_weight_kg' => 30.00,
                'base_price_per_day' => 800000,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table('type_room')->insert($typeRooms);
    }
}
