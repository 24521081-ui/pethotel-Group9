<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        DB::table('orders')->insert([
            ['order_id' => 'ORD001', 'customer_id' => 'CUS003', 'branch_id' => 'BR003', 'booking_id' => 'BKG001', 'created_by_emp' => 'EMP003', 'status' => 'PAID', 'subtotal' => 550000, 'grand_total' => 550000, 'created_at' => now()],
        ]);
    }
}
