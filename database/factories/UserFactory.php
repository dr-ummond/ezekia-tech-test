<?php

namespace Database\Factories;

use App\Enums\CurrencyTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'bio' => fake()->text(),
            'hourly_rate' => rand(1500, 8000),
            'currency' => Arr::random(CurrencyTypeEnum::cases())->value,
        ];
    }
}
