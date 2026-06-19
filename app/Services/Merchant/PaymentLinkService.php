<?php

declare(strict_types=1);

namespace App\Services\Merchant;

use App\Models\Enums\LockActiveStatus;
use App\Models\Merchant;
use App\Models\PaymentLink;
use App\Services\Merchant\Utils\PaymentLinkValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\URL;

class PaymentLinkService
{
    /**
     * Create a new payment link.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(Merchant $merchant, array $data): string
    {

        $paymentLink = PaymentLink::create([
            'merchant_id' => $merchant->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'] ?? null,
            'currency' => $data['currency'],
            'status' => LockActiveStatus::ACTIVE,
            'max_uses' => $data['max_uses'] ?? null,
            'uses_count' => 0,
            'expires_at' => $data['expires_at'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ]);

        $url = $this->generateLink($merchant, $paymentLink->refresh());

        $paymentLink->update(['url' => $url]);

        return $url;
    }

    /**
     * Get payment link by UUID.
     *
     * @throws ModelNotFoundException
     */
    public function getByUuid(string $uuid): PaymentLink
    {
        return PaymentLink::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Get all payment links for a merchant.
     *
     * @return Collection<int, PaymentLinks>
     */
    public function getAllForMerchant(Merchant $merchant): Collection
    {
        return $merchant->paymentLinks()->latest()->get();
    }

    /**
     * Update a payment link.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(PaymentLink $paymentLink, array $data): PaymentLink
    {
        if (isset($data['title'])) {
            $paymentLink->title = $data['title'];
        }
        if (array_key_exists('description', $data)) {
            $paymentLink->description = $data['description'];
        }
        if (array_key_exists('amount', $data)) {
            $paymentLink->amount = $data['amount'];
        }
        if (isset($data['currency'])) {
            $paymentLink->currency = $data['currency'];
        }
        if (isset($data['status'])) {
            $paymentLink->status = $data['status'];
        }
        if (array_key_exists('max_uses', $data)) {
            $paymentLink->max_uses = $data['max_uses'];
        }
        if (array_key_exists('expires_at', $data)) {
            $paymentLink->expires_at = $data['expires_at'];
        }
        if (array_key_exists('metadata', $data)) {
            $paymentLink->metadata = $data['metadata'];
        }

        $paymentLink->save();

        return $paymentLink->refresh();
    }

    /**
     * Check if payment link is valid for payment.
     */
    public function validateForPayment(PaymentLink $paymentLink): PaymentLinkValidator
    {
        return PaymentLinkValidator::make($paymentLink);
    }

    /**
     * Increment the uses count.
     */
    public function incrementUses(PaymentLink $paymentLink): void
    {
        $paymentLink->increment('uses_count');
    }

    private function generateLink(Merchant $merchant, PaymentLink $paymentLink): string
    {
        // Generate a secure signed URL that redirects to checkout
        // $paymentLink = $transaction->paymentLink;

        // Generate a signed URL valid for 30 days
        $signedUrl = URL::temporarySignedRoute(
            'merchant.pay',
            now()->addDays(30),
            [
                'ref' => $paymentLink->uuid,
                'merchant_name' => $merchant->business_name,
                'amount' => $paymentLink->amount,
                'currency' => $paymentLink->currency,
            ]
        );

        // Build the checkout redirect URL
        $checkoutUrl = config('services.checkout.url');

        // Extract the signed URL path and query string
        $parsedUrl = parse_url($signedUrl);
        $path = $parsedUrl['path'] ?? '';
        $query = $parsedUrl['query'] ?? '';

        // Build the secure checkout link
        $checkoutLink = $checkoutUrl.$path.($query ? '?'.$query : '');

        return $checkoutLink;
    }
}
