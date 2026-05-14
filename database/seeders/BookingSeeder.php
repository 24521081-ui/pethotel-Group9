<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('booking')->insert([
            ['booking_id' => 'BKG001', 'customer_id' => 'CUS003', 'branch_id' => 'BR003', 'checkin_expected_at' => now()->addDays(1), 'checkout_expected_at' => now()->addDays(3), 'status' => 'CONFIRMED', 'deposit_amount' => 100000, 'created_at' => now()],
        ]);
    }
}
