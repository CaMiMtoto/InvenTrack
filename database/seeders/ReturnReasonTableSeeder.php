<?php

namespace Database\Seeders;

use App\Models\ReturnReason;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReturnReasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (ReturnReason::query()->count() > 0) {
            return;
        }
        DB::table('return_reasons')->insert([
            [
                'code' => 'DAMAGED',
                'name' => 'Damaged Item',
                'description' => 'The product arrived damaged or broken.',
                'restockable' => false,
                'active' => true,
            ],
            [
                'code' => 'DEFECTIVE',
                'name' => 'Defective Product',
                'description' => 'The product is faulty or not working as expected.',
                'restockable' => false,
                'active' => true,
            ],
            [
                'code' => 'EXPIRED',
                'name' => 'Expired Product',
                'description' => 'The product is past its expiration date.',
                'restockable' => false,
                'active' => true,
            ],
            [
                'code' => 'WRONG_ITEM',
                'name' => 'Wrong Item Delivered',
                'description' => 'The item delivered does not match the order.',
                'restockable' => true,
                'active' => true,
            ],
            [
                'code' => 'CUSTOMER_REJECTED',
                'name' => 'Customer Rejected',
                'description' => 'Customer refused to accept the product upon delivery.',
                'restockable' => true,
                'active' => true,
            ],
            [
                'code' => 'PACKAGING_ISSUE',
                'name' => 'Packaging Issue',
                'description' => 'Product packaging was damaged or opened.',
                'restockable' => true,
                'active' => true,
            ],
            [
                'code' => 'EXCESS_SUPPLY',
                'name' => 'Excess Supply Returned',
                'description' => 'Extra or unsold items returned by client.',
                'restockable' => true,
                'active' => true,
            ],
        ]);
    }
}
