<?php

namespace App\Observers;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Storage;

class PaymentMethodObserver
{
    /**
     * Handle the PaymentMethod "created" event.
     */
    public function created(PaymentMethod $paymentMethod): void
    {
        //
    }

    /**
     * Handle the PaymentMethod "updated" event.
     */
    public function updated(PaymentMethod $paymentMethod): void
    {
        if ($paymentMethod->isDirty('logo')) {
            // Delete old logo
            Storage::disk('public')->delete($paymentMethod->getOriginal('logo'));
        }
    }

    /**
     * Handle the PaymentMethod "deleted" event.
     */
    public function deleted(PaymentMethod $paymentMethod): void
    {
        //
    }

    /**
     * Handle the PaymentMethod "restored" event.
     */
    public function restored(PaymentMethod $paymentMethod): void
    {
        //
    }

    /**
     * Handle the PaymentMethod "force deleted" event.
     */
    public function forceDeleted(PaymentMethod $paymentMethod): void
    {
        //
    }
}
