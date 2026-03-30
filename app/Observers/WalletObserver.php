<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Enums\ActivityLogAction;
use App\Models\Wallet;

class WalletObserver
{
    /**
     * Handle the Wallet "created" event.
     */
    public function created(Wallet $wallet): void
    {
        ActivityLog::create([
            'user_id' => $wallet->user_id,
            'action' => ActivityLogAction::WALLET_CREATED,
            'description' => 'Wallet created for currency: ' . $wallet->currency->value,
            'data' => [
                'wallet_id' => $wallet->id,
                'currency' => $wallet->currency,
                'initial_balance' => $wallet->balance,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle the Wallet "updated" event.
     */
    public function updated(Wallet $wallet): void
    {
        if ($wallet->isDirty('balance')) {
            $oldBalance = $wallet->getOriginal('balance');
            $newBalance = $wallet->balance;
            $difference = $newBalance - $oldBalance;

            $action = $difference > 0 ? ActivityLogAction::WALLET_CREDITED : ActivityLogAction::WALLET_DEBITED;

            ActivityLog::create([
                'user_id' => $wallet->user_id,
                'action' => $action,
                'description' => sprintf(
                    'Wallet balance changed from %s to %s (%s: %s)',
                    $oldBalance,
                    $newBalance,
                    $difference > 0 ? 'Credit' : 'Debit',
                    abs($difference)
                ),
                'data' => [
                    'wallet_id' => $wallet->id,
                    'currency' => $wallet->currency,
                    'old_balance' => $oldBalance,
                    'new_balance' => $newBalance,
                    'difference' => $difference,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        if ($wallet->isDirty('is_default') && $wallet->is_default) {
            ActivityLog::create([
                'user_id' => $wallet->user_id,
                'action' => ActivityLogAction::WALLET_SET_DEFAULT,
                'description' => 'Wallet set as default for currency: ' . $wallet->currency->value,
                'data' => [
                    'wallet_id' => $wallet->id,
                    'currency' => $wallet->currency,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    /**
     * Handle the Wallet "deleted" event.
     */
    public function deleted(Wallet $wallet): void
    {
        ActivityLog::create([
            'user_id' => $wallet->user_id,
            'action' => ActivityLogAction::WALLET_DELETED,
            'description' => 'Wallet deleted for currency: ' . $wallet->currency->value,
            'data' => [
                'wallet_id' => $wallet->id,
                'currency' => $wallet->currency,
                'final_balance' => $wallet->balance,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
