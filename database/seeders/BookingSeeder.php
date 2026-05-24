<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('booking')->insert([
            [
                'booking_id' => 1,
                'customer_id' => 1,
                'branch_id' => 1,
                'checkin_expected_at' => $now->copy()->addDay()->setTime(9, 0),
                'checkout_expected_at' => $now->copy()->addDays(3)->setTime(17, 0),
                'checkin_actual_at' => null,
                'checkout_actual_at' => null,
                'status' => 'CONFIRMED',
                'total_amount' => null,
                'special_notes' => 'Khách gửi 2 bé chung một phòng nhỏ.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'booking_id' => 2,
                'customer_id' => 2,
                'branch_id' => 1,
                'checkin_expected_at' => $now->copy()->addDays(2)->setTime(10, 0),
                'checkout_expected_at' => $now->copy()->addDays(4)->setTime(16, 0),
                'checkin_actual_at' => null,
                'checkout_actual_at' => null,
                'status' => 'PENDING',
                'total_amount' => null,
                'special_notes' => 'Booking chờ xác nhận cho bé Ben.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('booking_room')->insert([
            [
                'booking_room_id' => 1,
                'booking_id' => 1,
                'room_id' => 1,
                'assigned_at' => $now,
                'notes' => 'Gán phòng nhỏ cho Milo và Miu.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'booking_room_id' => 2,
                'booking_id' => 2,
                'room_id' => 2,
                'assigned_at' => $now,
                'notes' => 'Gán phòng vừa cho Ben.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('booking_room_pet')->insert([
            [
                'booking_room_pet_id' => 1,
                'booking_room_id' => 1,
                'pet_id' => 1,
                'notes' => 'Milo ở phòng nhỏ, cân nặng hợp lệ.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'booking_room_pet_id' => 2,
                'booking_room_id' => 1,
                'pet_id' => 2,
                'notes' => 'Miu ở cùng phòng nhỏ, không vượt slot.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'booking_room_pet_id' => 3,
                'booking_room_id' => 2,
                'pet_id' => 3,
                'notes' => 'Ben ở phòng vừa, cân nặng hợp lệ.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('booking_service_pet')->insert([
            [
                'booking_service_pet_id' => 1,
                'booking_id' => 1,
                'pet_id' => 1,
                'service_id' => 1,
                'employee_id' => 2,
                'scheduled_at' => $now->copy()->addDay()->setTime(14, 0),
                'status' => 'ASSIGNED',
                'notes' => 'Tắm sấy cho Milo.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'booking_service_pet_id' => 2,
                'booking_id' => 2,
                'pet_id' => 3,
                'service_id' => 3,
                'employee_id' => null,
                'scheduled_at' => $now->copy()->addDays(2)->setTime(11, 0),
                'status' => 'PENDING',
                'notes' => 'Kiểm tra sức khỏe cho Ben.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
