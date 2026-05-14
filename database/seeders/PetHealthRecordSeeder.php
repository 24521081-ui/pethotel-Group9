<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetHealthRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        DB::table('pet_health_record')->insert([
            ['health_record_id' => 'PHR001', 'pet_id' => 'PET003', 'booking_id' => 'BKG001', 'note' => 'Bé khỏe mạnh, ăn uống tốt', 'status' => 1],
        ]);
    }
}
