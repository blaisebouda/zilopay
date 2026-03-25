<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['fiat', 'crypto']),
            'name' => $this->faker->word(),
            'symbol' => $this->faker->word(),
            'code' => $this->faker->word(),
            'rate' => $this->faker->randomFloat(2, 0, 1),
            'logo' => $this->faker->word(),
            'exchange_from' => 'local',
            'default' => $this->faker->boolean(),
            'status' => $this->faker->boolean(),
        ];
    }
}
