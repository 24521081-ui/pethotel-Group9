<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        DB::table('payments')->insert([
            ['payment_id' => 'PAY001', 'order_id' => 'ORD001', 'payment_method' => 'CASH', 'amount' => 550000, 'status' => 'SUCCESS', 'paid_at' => now(), 'created_at' => now()],
        ]);
    }
}
