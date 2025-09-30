<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'address' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'tin' => $this->faker->numberBetween(100000000,999999999),
            'name' => $this->faker->name(),
        ];
    }
}
