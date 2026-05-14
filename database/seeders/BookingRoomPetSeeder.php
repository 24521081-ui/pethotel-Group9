<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingRoomPetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('booking_room_pet')->insert([
            ['booking_room_id' => 'BKR001', 'pet_id' => 'PET003', 'assigned_at' => now()],
        ]);
    }
}
