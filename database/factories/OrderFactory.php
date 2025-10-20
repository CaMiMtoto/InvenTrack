<?php

namespace Database\Factories;

use App\Constants\Status;
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
            'total_amount' => fake()->numberBetween(10000, 9999999),
            'order_status' => fake()->randomElement([Status::Approved]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order_date' => fake()->dateTimeBetween('-2 month', 'now'),
            'customer_id' => Customer::query()->inRandomOrder()->first()->id,
            'order_number'=>fake()->buildingNumber()
        ];
    }
}
