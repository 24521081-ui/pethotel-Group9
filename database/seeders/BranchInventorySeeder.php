<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventories = [
            // Chi nhánh BR003 (Pet Spa Quận 7)
            ['branch_id' => 'BR003', 'product_id' => 'PROD02', 'quantity_in_stock' => 20, 'reorder_point' => 5],
            ['branch_id' => 'BR003', 'product_id' => 'PROD03', 'quantity_in_stock' => 15, 'reorder_point' => 3],
            ['branch_id' => 'BR003', 'product_id' => 'PROD04', 'quantity_in_stock' => 30, 'reorder_point' => 10],
            ['branch_id' => 'BR003', 'product_id' => 'PROD05', 'quantity_in_stock' => 100, 'reorder_point' => 20],

            // Chi nhánh BR004 (Pet Spa Gò Vấp)
            ['branch_id' => 'BR004', 'product_id' => 'PROD06', 'quantity_in_stock' => 10, 'reorder_point' => 2],
            ['branch_id' => 'BR004', 'product_id' => 'PROD07', 'quantity_in_stock' => 25, 'reorder_point' => 5],
            ['branch_id' => 'BR004', 'product_id' => 'PROD08', 'quantity_in_stock' => 40, 'reorder_point' => 8],
            ['branch_id' => 'BR004', 'product_id' => 'PROD09', 'quantity_in_stock' => 50, 'reorder_point' => 10],

            // Chi nhánh BR005 (Pet Spa Bình Dương)
            ['branch_id' => 'BR005', 'product_id' => 'PROD10', 'quantity_in_stock' => 25, 'reorder_point' => 5],
            ['branch_id' => 'BR005', 'product_id' => 'PROD11', 'quantity_in_stock' => 5, 'reorder_point' => 2],
            ['branch_id' => 'BR005', 'product_id' => 'PROD02', 'quantity_in_stock' => 12, 'reorder_point' => 5],
            ['branch_id' => 'BR005', 'product_id' => 'PROD03', 'quantity_in_stock' => 8, 'reorder_point' => 3],
        ];

        DB::table('branch_inventory')->insert($inventories);
    }
}
