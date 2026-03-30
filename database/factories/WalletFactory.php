<?php

namespace Database\Factories;

use App\Models\Enums\CommonStatus;
use App\Models\Enums\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'currency' => Currency::random(),
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'is_default' => $this->faker->boolean(),
            'status' => CommonStatus::random(),
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}
