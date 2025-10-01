<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Account::exists())
            return;

        $accounts = [
            ['name' => 'Inventory', 'type' => 'asset'],
            ['name' => 'Accounts Payable', 'type' => 'liability'],
            ['name' => 'Cash', 'type' => 'asset'],
            ['name' => 'Bank', 'type' => 'asset'],
            ['name' => 'Sales Revenue', 'type' => 'income'],
            ['name' => 'Cost of Goods Sold', 'type' => 'expense'],
            ['name' => 'Discounts Given', 'type' => 'expense'],
            ['name' => 'Discounts Received', 'type' => 'income'],
            ['name' => 'Expenses – General', 'type' => 'expense'],
        ];

        foreach ($accounts as $acc) {
            Account::firstOrCreate(['name' => $acc['name']], ['type' => $acc['type']]);
        }
    }
}
