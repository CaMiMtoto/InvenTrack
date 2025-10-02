<?php

namespace Database\Seeders;

use App\Models\Account;
use DB;
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
        $now = now();
        $accounts = [
            // Assets
            ['name' => 'Inventory', 'type' => 'asset', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cash', 'type' => 'asset', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bank', 'type' => 'asset', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Accounts Receivable', 'type' => 'asset', 'created_at' => $now, 'updated_at' => $now],

            // Liabilities
            ['name' => 'Accounts Payable', 'type' => 'liability', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tax Payable', 'type' => 'liability', 'created_at' => $now, 'updated_at' => $now],

            // Equity
            ['name' => 'Owner’s Equity', 'type' => 'equity', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Retained Earnings', 'type' => 'equity', 'created_at' => $now, 'updated_at' => $now],

            // Income
            ['name' => 'Sales Revenue', 'type' => 'income', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Discounts Received', 'type' => 'income', 'created_at' => $now, 'updated_at' => $now],

            // Expenses
            ['name' => 'Cost of Goods Sold', 'type' => 'expense', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Discounts Given', 'type' => 'expense', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Expenses – General', 'type' => 'expense', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Purchases', 'type' => 'expense', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('accounts')->insert($accounts);
    }
}
