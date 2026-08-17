<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Enums\Country;
use App\Models\Enums\Currency;
use App\Models\Enums\LockActiveStatus;
use App\Models\Enums\MerchantStatus;
use App\Models\Enums\PaymentMethodCode;
use App\Models\Enums\PaymentMethodType;
use App\Models\Enums\TransactionStatus;
use App\Models\Enums\TransactionType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestDataController extends Controller
{
    public function seed(Request $request)
    {
        if (! app()->environment('local')) {
            abort(404);
        }

        // Payment methods (safe updateOrCreate)
        $methods = [
            [
                'name' => 'Wave',
                'code' => PaymentMethodCode::WAVE->value,
                'type' => PaymentMethodType::MOBILE_MONEY->value,
                'min_amount' => 100,
                'max_amount' => 1000000,
                'fee_percent' => 1.0,
                'fee_fixed' => 0,
                'country' => Country::BF->value,
                'logo' => PAYMENT_METHOD_LOGO_PATH . '/wave.png',
            ],
            [
                'name' => 'Orange Money',
                'code' => PaymentMethodCode::ORANGE_MONEY->value,
                'type' => PaymentMethodType::MOBILE_MONEY->value,
                'min_amount' => 100,
                'max_amount' => 500000,
                'fee_percent' => 1.5,
                'fee_fixed' => 0,
                'country' => Country::BF->value,
                'logo' => PAYMENT_METHOD_LOGO_PATH . '/orange-money.png',
            ],
            [
                'name' => 'Moov Money',
                'code' => PaymentMethodCode::MOOV_MONEY->value,
                'type' => PaymentMethodType::MOBILE_MONEY->value,
                'min_amount' => 100,
                'max_amount' => 500000,
                'fee_percent' => 1.5,
                'fee_fixed' => 0,
                'country' => Country::BF->value,
                'logo' => PAYMENT_METHOD_LOGO_PATH . '/moov-money.png',
            ],
            [
                'name' => 'Orange CI',
                'code' => PaymentMethodCode::ORANGE_CI->value,
                'type' => PaymentMethodType::MOBILE_MONEY->value,
                'min_amount' => 100,
                'max_amount' => 500000,
                'fee_percent' => 1.5,
                'fee_fixed' => 0,
                'country' => Country::CI->value,
                'logo' => PAYMENT_METHOD_LOGO_PATH . '/orange-money.png',
            ],
            [
                'name' => 'Wave SN',
                'code' => PaymentMethodCode::WAVE_SN->value,
                'type' => PaymentMethodType::MOBILE_MONEY->value,
                'min_amount' => 100,
                'max_amount' => 1000000,
                'fee_percent' => 1.0,
                'fee_fixed' => 0,
                'country' => Country::SN->value,
                'logo' => PAYMENT_METHOD_LOGO_PATH . '/wave.png',
            ],
        ];

        foreach ($methods as $m) {
            PaymentMethod::updateOrCreate(
                ['code' => $m['code']],
                $m
            );
        }

        // Users (no faker) et leurs wallets
        $users = [
            ['name' => 'Zilo Pay User', 'email' => 'user@zilopay.com', 'phone' => '22670000000', 'role' => 'user', 'balance' => 888500, 'code' => 'ZP00000000'],
            ['name' => 'Test User', 'email' => 'test@zilopay.com', 'phone' => '22670000001', 'role' => 'user', 'balance' => 500000, 'code' => 'ZP00000001'],
            ['name' => 'Admin', 'email' => 'admin@zilopay.com', 'phone' => '22670707070', 'role' => 'admin', 'balance' => 1000000, 'code' => 'ZP00000002'],
            ['name' => 'Merchant', 'email' => 'merchant@zilopay.com', 'phone' => '22670707071', 'role' => 'merchant', 'balance' => 1000000, 'code' => 'ZP00000003'],
        ];

        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'phone_number' => $u['phone'],
                    'role' => $u['role'],
                    'password' => Hash::make('password'),
                    'policy_accepted_at' => Carbon::now(),
                ]
            );

            $wallet = Wallet::getDefaultForUser($user->id);
            if (! $wallet) {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'currency' => Currency::XOF->value,
                    'balance' => 0,
                    'is_default' => true,
                    'status' => LockActiveStatus::ACTIVE->value,
                ]);
            }

            if (! empty($u['balance']) && $u['balance'] > 0) {
                $wallet->credit($u['balance']);
            }

            $wallet->update(['code' => $u['code']]);
        }

        // Merchant approval
        $merchantUser = User::where('email', 'merchant@zilopay.com')->first();
        $adminUser = User::where('email', 'admin@zilopay.com')->first();

        if ($merchantUser) {
            Merchant::updateOrCreate(
                ['user_id' => $merchantUser->id],
                [
                    'business_name' => 'Merchant Business',
                    'business_email' => $merchantUser->email,
                    'phone_number' => $merchantUser->phone_number,
                    'country' => Country::BF->value,
                    'currency' => Currency::XOF->value,
                    'fee_fixed' => 0,
                    'fee_percent' => 0,
                    'status' => MerchantStatus::APPROVED->value,
                    'approved_at' => Carbon::now(),
                    'approved_by' => $adminUser?->id,
                ]
            );
        }

        // Transactions (deterministic)
        $mainUser = User::where('email', 'user@zilopay.com')->first();
        $testUser = User::where('email', 'test@zilopay.com')->first();

        $wallet1 = $mainUser ? Wallet::getDefaultForUser($mainUser->id) : null;
        $wallet2 = $testUser ? Wallet::getDefaultForUser($testUser->id) : null;

        $pmWave = PaymentMethod::where('code', PaymentMethodCode::WAVE->value)->first();

        if ($mainUser && $wallet1 && $wallet2 && $pmWave) {
            $txs = [
                ['uuid' => 'seed-deposit-pending', 'user_id' => $mainUser->id, 'payment_method_id' => $pmWave->id, 'type' => TransactionType::DEPOSIT->value, 'amount' => 1000, 'platform_fee_amount' => 10, 'net_amount' => 1010, 'status' => TransactionStatus::PENDING->value, 'currency' => 'XOF'],
                ['uuid' => 'seed-withdrawal-failed', 'user_id' => $mainUser->id, 'payment_method_id' => $pmWave->id, 'type' => TransactionType::WITHDRAWAL->value, 'amount' => 500, 'platform_fee_amount' => 5, 'net_amount' => 505, 'status' => TransactionStatus::FAILED->value, 'currency' => 'XOF'],
                ['uuid' => 'seed-transfer-success', 'user_id' => $mainUser->id, 'payment_method_id' => $pmWave->id, 'type' => TransactionType::TRANSFER->value, 'amount' => 250, 'platform_fee_amount' => 2.5, 'net_amount' => 252.5, 'status' => TransactionStatus::SUCCESS->value, 'currency' => 'XOF'],
            ];

            foreach ($txs as $t) {
                // Ensure uuid column receives a valid UUID (Postgres uuid type)
                if (! preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $t['uuid'])) {
                    $seed = $t['uuid'];
                    $h = md5($seed);
                    $t['uuid'] = sprintf('%08s-%04s-%04s-%04s-%12s', substr($h, 0, 8), substr($h, 8, 4), substr($h, 12, 4), substr($h, 16, 4), substr($h, 20, 12));
                }

                Transaction::updateOrCreate([
                    'uuid' => $t['uuid'],
                ], $t);
            }
        }

        return response()->json(['status' => 'ok', 'message' => 'Test data seeded (idempotent)']);
    }
}
