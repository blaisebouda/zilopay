<?php

namespace Database\Seeders;

use App\Models\Enums\PaymentMethodCode;
use App\Models\Enums\PaymentMethodType;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        PaymentMethod::insert([
            [
                'name' => 'Wave',
                'code' => PaymentMethodCode::WAVE,
                'type' => PaymentMethodType::MOBILE_MONEY,
                'min_amount' => 100,
                'max_amount' => 1000000,
                'fee_percent' => 1.0,
                'fee_fixed' => 0,
                'country_id' => 1,
                'logo' => 'wave.png',
            ],
            [
                'name' => 'Orange Money',
                'code' => PaymentMethodCode::ORANGE_MONEY,
                'type' => PaymentMethodType::MOBILE_MONEY,
                'min_amount' => 100,
                'max_amount' => 500000,
                'fee_percent' => 1.5,
                'fee_fixed' => 0,
                'country_id' => 1,
                'logo' => 'orange-money.png',
            ],
            [
                'name' => 'Moov Money',
                'code' => PaymentMethodCode::MOOV_MONEY,
                'type' => PaymentMethodType::MOBILE_MONEY,
                'min_amount' => 100,
                'max_amount' => 500000,
                'fee_percent' => 1.5,
                'fee_fixed' => 0,
                'country_id' => 1,
                'logo' => 'moov-money.png',
            ],
            // [
            //     'name' => 'Telecel Money',
            //     'code' => PaymentMethodCode::TELECEL_MONEY,
            //     'type' => PaymentMethodType::MOBILE_MONEY,
            //     'min_amount' => 100,
            //     'max_amount' => 500000,
            //     'fee_percent' => 1.5,
            //     'fee_fixed' => 0,
            //     'country_id' => 1,
            //     'logo' => 'telecel-money.png',
            // ],
            // [
            //     'name' => 'Virement Bancaire',
            //     'code' => PaymentMethodCode::BANK_TRANSFER,
            //     'type' => PaymentMethodType::BANK_TRANSFER,
            //     'min_amount' => 10000,
            //     'max_amount' => 10000000,
            //     'fee_percent' => 0,
            //     'fee_fixed' => 500,
            //     'country_id' => 1,
            //     'logo' => 'bank-transfer.png',
            // ],
        ]);
    }
}
