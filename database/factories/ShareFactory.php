<?php

namespace Database\Factories;

use App\Models\Share;
use App\Models\Shareholder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ShareFactory extends Factory
{
    protected $model = Share::class;

    public function definition(): array
    {
        return [
            'value' => config('services.shares.value'),
            'quantity' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'created_at' => $this->faker->dateTimeBetween('-2 weeks', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('now', 'now'),
            'shareholder_id' => Shareholder::query()->inRandomOrder()->first()->id,
            'user_id' => User::query()->inRandomOrder()->first()->id
        ];
    }
}
