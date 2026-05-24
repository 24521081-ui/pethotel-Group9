<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin Demo',
                'email' => 'admin@pethotel.test',
                'email_verified_at' => $now,
                'password' => Hash::make('password123'),
                'role' => 'ADMIN',
                'is_active' => true,
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Le Tan Demo',
                'email' => 'receptionist@pethotel.test',
                'email_verified_at' => $now,
                'password' => Hash::make('password123'),
                'role' => 'RECEPTIONIST',
                'is_active' => true,
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Groomer Demo',
                'email' => 'groomer@pethotel.test',
                'email_verified_at' => $now,
                'password' => Hash::make('password123'),
                'role' => 'GROOMER',
                'is_active' => true,
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Manager Demo',
                'email' => 'manager@pethotel.test',
                'email_verified_at' => $now,
                'password' => Hash::make('password123'),
                'role' => 'MANAGER',
                'is_active' => true,
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'Nguyen Van A',
                'email' => 'customer1@pethotel.test',
                'email_verified_at' => $now,
                'password' => Hash::make('password123'),
                'role' => 'CUSTOMER',
                'is_active' => true,
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'name' => 'Tran Thi B',
                'email' => 'customer2@pethotel.test',
                'email_verified_at' => $now,
                'password' => Hash::make('password123'),
                'role' => 'CUSTOMER',
                'is_active' => true,
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
