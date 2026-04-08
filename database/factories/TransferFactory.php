<?php

namespace Database\Factories;

use App\Models\Enums\CommonStatus;
use App\Models\Enums\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wallet>
 */
class TransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'sender_wallet_id' => Wallet::factory(),
            'sender_wallet_id' => Wallet::factory(),
            'note' => $this->faker->text(),

        ];
    }
}
