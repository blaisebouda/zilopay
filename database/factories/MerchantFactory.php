<?php

namespace Database\Factories;

use App\Models\Enums\Country;
use App\Models\Enums\Currency;
use App\Models\Enums\MerchantStatus;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Merchant>
 */
class MerchantFactory extends Factory
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
            'business_name' => $this->faker->company,
            'business_email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'country' => Country::BF->value,
            'currency' => Currency::XOF->value,
            'fee_fixed' => $this->faker->randomFloat(2, 0, 100),
            'fee_percent' => $this->faker->randomFloat(2, 0, 100),
            'status' => MerchantStatus::random(),
            'approved_at' => $this->faker->dateTime,
            'approved_by' => User::factory(),
            'rejection_reason' => $this->faker->text,
        ];
    }

    public function approve()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => MerchantStatus::APPROVED->value,
            ];
        });
    }
}
