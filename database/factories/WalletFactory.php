<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Enums\ModelStatus;
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
            'currency_id' => Currency::factory(),
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'is_default' => $this->faker->boolean(),
            'status' => ModelStatus::random(),
        ];
    }
}
