<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'created_by' => User::query()->inRandomOrder()->first()->id,
            'total_amount' => fake()->numberBetween(10000, 999999999),
            'order_status' => fake()->randomElement(['pending', 'approved', 'assigned', 'delivered', 'reconciled', 'completed']),
            'payment_status' => fake()->randomElement(['unpaid', 'partial', 'paid']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order_date' => fake()->dateTimeBetween('-2 month', 'now'),
            'customer_id' => Customer::query()->inRandomOrder()->first()->id,
            'order_number'=>fake()->buildingNumber()
        ];
    }
}
