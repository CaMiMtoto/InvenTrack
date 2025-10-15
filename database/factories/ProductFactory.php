<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'is_active' => $this->faker->boolean(),
            'min_stock' => $this->faker->randomNumber(),
            'image' => fake()->imageUrl(),
            'stock' => $this->faker->randomNumber(),
            'price' => $this->faker->numberBetween(1000,100000),
            'description' => $this->faker->text(),
            'category_id' => Category::query()->inRandomOrder()->first()->id,
            'name' => $this->faker->name(),
            'sku' => fake()->postcode(),
            'unit_measure' => fake()->randomElement(['Ml', 'Kg', 'Bottle', 'Piece'])
        ];
    }
}
