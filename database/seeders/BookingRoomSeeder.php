<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('booking_room')->insert([
            ['booking_room_id' => 'BKR001', 'booking_id' => 'BKG001', 'room_id' => 'RM102', 'assigned_at' => now()],
        ]);
    }
}
