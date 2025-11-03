<?php

namespace Database\Factories;

use App\Models\LegalType;
use App\Models\Shareholder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShareholderFactory extends Factory
{
    protected $model = Shareholder::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'legal_type_id' => LegalType::query()->inRandomOrder()->first()->id,
            'id_number' => fake()->postcode(),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'tin' => fake()->numberBetween(111111111, 999999999),
            'birth_date' => fake()->dateTimeBetween('-20 years', '-18 years'),
            'nationality' => fake()->country(),
            'residential_address' => fake()->address()
        ];
    }
}
