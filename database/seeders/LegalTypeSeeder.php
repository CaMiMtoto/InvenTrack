<?php

namespace Database\Seeders;

use App\Models\LegalType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LegalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legalTypes = [
            ['name' => 'National ID'],
            ['name' => 'TIN'],
        ];

        if (LegalType::query()->exists())
            return;

        foreach ($legalTypes as $item) {
            LegalType::query()
                ->create($item);
        }

    }
}
