<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'created_by' => $this->faker->randomNumber(),
            'total_amount' => $this->faker->word(),
            'order_status' => $this->faker->word(),
            'payment_status' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order_date' => $this->faker->word(),
            'customer_id' => Customer::factory(),
        ];
    }
}
