<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingServicePetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        DB::table('booking_services_pet')->insert([
            ['booking_service_id' => 'BSP001', 'booking_id' => 'BKG001', 'service_id' => 'SER002', 'employee_id' => 'EMP003', 'pet_id' => 'PET003', 'status' => 'PENDING', 'created_at' => now()],
        ]);
    }
}
