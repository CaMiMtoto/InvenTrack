<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::query()->inRandomOrder()->first()->id,
            'invoice_number' => fake()->swiftBicNumber(),
            'total_amount' => fake()->numberBetween(10000, 99999999),
            'purchased_at' => now(),
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
