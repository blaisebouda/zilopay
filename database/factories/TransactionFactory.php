<?php

namespace Database\Factories;

use App\Models\Enums\Currency;
use App\Models\Enums\TransactionStatus;
use App\Models\Enums\TransactionType;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomFloat(8, 10, 10000);
        $feeFixed = fake()->randomFloat(8, 0, 50);
        $feePercentage = fake()->randomFloat(8, 0, 5);
        $total = $amount + $feeFixed + ($amount * $feePercentage / 100);

        return [
            'uuid' => Str::uuid(),
            'user_id' => User::factory(),
            'currency' => Currency::XOF->value,
            'payment_method_id' => fake()->randomElement([1, 2, 3]),
            'type' => fake()->randomElement(TransactionType::cases())->value,
            'amount' => $amount,
            'fee_fixed' => $feeFixed,
            'fee_percentage' => $feePercentage,
            'total' => $total,
            'status' => fake()->randomElement(TransactionStatus::cases())->value,

        ];
    }

    /**
     * Indicate that the transaction is a deposit.
     */
    public function deposit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::DEPOSIT->value,
        ]);
    }

    /**
     * Indicate that the transaction is a withdrawal.
     */
    public function withdrawal(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::WITHDRAWAL->value,
        ]);
    }

    /**
     * Indicate that the transaction is a transfer.
     */
    public function transfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::TRANSFER->value,
        ]);
    }

    /**
     * Indicate that the transaction is a payment.
     */
    public function payment(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::PAYMENT->value,
        ]);
    }

    /**
     * Indicate that the transaction is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::PENDING->value,
        ]);
    }

    /**
     * Indicate that the transaction is successful.
     */
    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::SUCCESS->value,
        ]);
    }

    /**
     * Indicate that the transaction is refunded.
     */
    public function refund(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::REFUND->value,
        ]);
    }

    /**
     * Indicate that the transaction is blocked.
     */
    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::BLOCKED->value,
        ]);
    }

    /**
     * Indicate that the transaction has a payment method.
     */
    public function withPaymentMethod(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method_id' => PaymentMethod::factory(),
        ]);
    }
}
