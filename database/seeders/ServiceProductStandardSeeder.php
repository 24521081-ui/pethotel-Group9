<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceProductStandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        DB::table('service_product_standard')->insert([
            ['standard_id' => 'STD001', 'service_id' => 'SER010', 'product_id' => 'PROD03', 'species' => 'DOG', 'min_weight_kg' => 0, 'max_weight_kg' => 15, 'usage_amount' => 10, 'usage_unit' => 'ML', 'created_at' => now()],
        ]);
    }
}
