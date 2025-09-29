<?php

namespace Database\Factories;

use App\Models\PurchaseItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PurchaseItemFactory extends Factory
{
    protected $model = PurchaseItem::class;

    public function definition(): array
    {
        return [
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'unit_price' => $this->faker->randomFloat(),
            'quantity' => $this->faker->randomNumber(),
            'product_id' => $this->faker->randomNumber(),
            'purchase_id' => $this->faker->randomNumber(),
        ];
    }
}
