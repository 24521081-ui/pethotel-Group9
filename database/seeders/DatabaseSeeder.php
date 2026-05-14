<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            // ==========================================
            // NHÓM 1: CÁC BẢNG DANH MỤC & ĐỘC LẬP (Không chứa khóa ngoại)
            // ==========================================
            CategoryProductSeeder::class,
            CategoryServiceSeeder::class,
            TypeRoomSeeder::class,
            BranchSeeder::class,
            CustomerSeeder::class,

            // ==========================================
            // NHÓM 2: PHỤ THUỘC CẤP 1 (Cần dữ liệu từ Nhóm 1)
            // ==========================================
            ProductSeeder::class,     // Cần category_product
            ServiceSeeder::class,     // Cần category_services
            EmployeeSeeder::class,    // Cần branch
            RoomSeeder::class,        // Cần branch, type_room
            PetSeeder::class,         // Cần customer

            // ==========================================
            // NHÓM 3: PHỤ THUỘC CẤP 2 (Core logic hoạt động)
            // ==========================================
            BranchInventorySeeder::class,          // Cần branch, product
            ServiceProductStandardSeeder::class,   // Cần services, product
            UserSeeder::class,                     // Cần employee, customer (bảng users đã độ lại)
            BookingSeeder::class,                  // Cần customer, branch

            // ==========================================
            // NHÓM 4: GIAO DỊCH DỊCH VỤ & PHÒNG CHỜ
            // ==========================================
            BookingRoomSeeder::class,              // Cần booking, room
            BookingServicePetSeeder::class,        // Cần booking, services, employee, pet
            PetHealthRecordSeeder::class,          // Cần pet, booking
            OrderSeeder::class,                    // Cần customer, branch, employee, booking

            // ==========================================
            // NHÓM 5: CHI TIẾT CUỐI CÙNG & THANH TOÁN
            // ==========================================
            BookingRoomPetSeeder::class,           // Cần booking_room, pet
            OrderDetailSeeder::class,              // Cần orders, booking_room, booking_service_pet
            PaymentSeeder::class,                  // Cần orders
        ]);
    }
}
