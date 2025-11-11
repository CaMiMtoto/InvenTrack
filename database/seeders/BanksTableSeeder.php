<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            'I&M Bank Rwanda Plc',
            'Bank of Kigali Plc (BK)',
            'BPR Bank',
            'Guaranty Trust Bank Plc(GT)',
            'Ecobank Rwanda Plc',
            'Access Bank Rwanda',
            'Equity Bank Rwanda Plc',
            'Bank of Africa Rwanda',
            'NCBA Rwanda Plc',
            'Zigama Credit and Savings Bank (ZCSB)',
            'AB Bank Rwanda',
            'Unguka Bank',
        ];
        if (Bank::query()->exists())
            return;

        foreach ($banks as $bank) {
            Bank::query()->create([
                'name' => $bank
            ]);
       }
    }
}
