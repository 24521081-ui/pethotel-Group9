<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $pets = [
            ['pet_id' => 'PET003', 'customer_id' => 'CUS003', 'pet_name' => 'Kiki', 'species' => 'DOG', 'breed' => 'Husky', 'weight_kg' => 20.5, 'created_at' => $now, 'updated_at' => $now],
            ['pet_id' => 'PET004', 'customer_id' => 'CUS004', 'pet_name' => 'Meo Meo', 'species' => 'CAT', 'breed' => 'Persian', 'weight_kg' => 3.8, 'created_at' => $now, 'updated_at' => $now],
            ['pet_id' => 'PET005', 'customer_id' => 'CUS005', 'pet_name' => 'Bắp', 'species' => 'DOG', 'breed' => 'Golden Retriever', 'weight_kg' => 25.0, 'created_at' => $now, 'updated_at' => $now],
            ['pet_id' => 'PET006', 'customer_id' => 'CUS006', 'pet_name' => 'Xù', 'species' => 'DOG', 'breed' => 'Phú Quốc', 'weight_kg' => 18.2, 'created_at' => $now, 'updated_at' => $now],
            ['pet_id' => 'PET007', 'customer_id' => 'CUS007', 'pet_name' => 'Mướp', 'species' => 'CAT', 'breed' => 'Tabby', 'weight_kg' => 4.0, 'created_at' => $now, 'updated_at' => $now],
            ['pet_id' => 'PET008', 'customer_id' => 'CUS008', 'pet_name' => 'Lu', 'species' => 'DOG', 'breed' => 'Corgi', 'weight_kg' => 12.5, 'created_at' => $now, 'updated_at' => $now],
            ['pet_id' => 'PET009', 'customer_id' => 'CUS009', 'pet_name' => 'Chirpy', 'species' => 'BIRD', 'breed' => 'Parrot', 'weight_kg' => 0.5, 'created_at' => $now, 'updated_at' => $now],
            ['pet_id' => 'PET010', 'customer_id' => 'CUS010', 'pet_name' => 'Bông', 'species' => 'CAT', 'breed' => 'Maine Coon', 'weight_kg' => 8.5, 'created_at' => $now, 'updated_at' => $now],
            ['pet_id' => 'PET011', 'customer_id' => 'CUS011', 'pet_name' => 'Gấu', 'species' => 'DOG', 'breed' => 'Chihuahua', 'weight_kg' => 2.1, 'created_at' => $now, 'updated_at' => $now],
            ['pet_id' => 'PET012', 'customer_id' => 'CUS012', 'pet_name' => 'Kem', 'species' => 'CAT', 'breed' => 'Ragdoll', 'weight_kg' => 5.2, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('pet')->insert($pets);
    }
}
