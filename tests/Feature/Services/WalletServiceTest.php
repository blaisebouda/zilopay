<?php

use App\Models\User;
use App\Models\Wallet;
use App\Services\Wallet\WalletService;

beforeEach(function () {
    /** @var User $user */
    $this->user = User::factory()->create();
});

describe('createDefaultWallet', function () {
    it('creates a default wallet with XOF currency', function () {
        $currency = Currency::factory()->create([
            'code' => 'XOF',
            'default' => true,
        ]);

        $wallet = WalletService::createDefaultWallet($this->user);

        expect($wallet)->toBeInstanceOf(Wallet::class);
        expect($wallet->user_id)->toBe($this->user->id);
        expect($wallet->currency_id)->toBe($currency->id);
        expect($wallet->balance)->toBe(0.00);
        expect($wallet->is_default)->toBeTrue();
    });

    it('uses the default currency when XOF is not found', function () {
        $defaultCurrency = Currency::factory()->create([
            'code' => 'USD',
            'default' => true,
        ]);

        $wallet = WalletService::createDefaultWallet($this->user);

        expect($wallet->currency_id)->toBe($defaultCurrency->id);
    });
});

describe('createWallet', function () {
    it('creates a wallet for a specific currency', function () {
        $currency = Currency::factory()->create();

        $wallet = WalletService::createWallet($this->user, $currency->id);

        expect($wallet)->toBeInstanceOf(Wallet::class);
        expect($wallet->user_id)->toBe($this->user->id);
        expect($wallet->currency_id)->toBe($currency->id);
        expect($wallet->balance)->toBe(0.0);
        expect($wallet->is_default)->toBeFalse();
    });

    it('throws exception when wallet already exists for currency', function () {
        $currency = Currency::factory()->create();
        Wallet::factory()->create([
            'user_id' => $this->user->id,
            'currency_id' => $currency->id,
        ]);

        expect(fn () => WalletService::createWallet($this->user, $currency->id))
            ->toThrow(Exception::class, 'Wallet already exists for this currency');
    });
});

describe('setDefaultWallet', function () {
    it('sets a wallet as default and unsets previous default', function () {
        $currency1 = Currency::factory()->create();
        $currency2 = Currency::factory()->create();

        $oldDefault = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'currency_id' => $currency1->id,
            'is_default' => true,
        ]);

        $newDefault = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'currency_id' => $currency2->id,
            'is_default' => false,
        ]);

        WalletService::setDefaultWallet($newDefault);

        expect($newDefault->fresh()->is_default)->toBeTrue();
        expect($oldDefault->fresh()->is_default)->toBeFalse();
    });

    it('sets wallet as default when no previous default exists', function () {
        $currency = Currency::factory()->create();
        $wallet = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'currency_id' => $currency->id,
            'is_default' => false,
        ]);

        WalletService::setDefaultWallet($wallet);

        expect($wallet->fresh()->is_default)->toBeTrue();
    });
});
