<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {

        $branches = [
            [
                'branch_id' => 'BR003',
                'branch_name' => 'Pet Spa Quận 7',
                'phone' => '0283344556',
                'email' => 'q7@petspa.vn',
                'address' => 'Nguyễn Văn Linh, Q7',
                'is_active' => 1

            ],
            [
                'branch_id' => 'BR004',
                'branch_name' => 'Pet Spa Gò Vấp',
                'phone' => '0283344557',
                'email' => 'govap@petspa.vn',
                'address' => 'Quang Trung, Gò Vấp',
                'is_active' => 1

            ],
            [
                'branch_id' => 'BR005',
                'branch_name' => 'Pet Spa Bình Dương',
                'phone' => '0274334455',
                'email' => 'bd@petspa.vn',
                'address' => 'Đại lộ Bình Dương',
                'is_active' => 1

            ],
        ];

        DB::table('branch')->insert($branches);
    }
}
