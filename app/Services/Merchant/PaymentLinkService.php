<?php

declare(strict_types=1);

namespace App\Services\Merchant;

use App\Models\Enums\CommonStatus;
use App\Models\Merchant;
use App\Models\PaymentLinks;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentLinkService
{
    /**
     * Create a new payment link.
     *
     * @param array<string, mixed> $data
     */
    public function create(Merchant $merchant, array $data): PaymentLinks
    {
        $paymentLink = PaymentLinks::create([
            'merchant_id' => $merchant->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'] ?? null,
            'currency' => $data['currency'],
            'status' => CommonStatus::ACTIVE,
            'max_uses' => $data['max_uses'] ?? null,
            'uses_count' => 0,
            'expires_at' => $data['expires_at'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ]);

        return $paymentLink->fresh();
    }

    /**
     * Get payment link by UUID.
     *
     * @throws ModelNotFoundException
     */
    public function getByUuid(string $uuid): PaymentLinks
    {
        return PaymentLinks::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Get all payment links for a merchant.
     *
     * @return Collection<int, paymentLinks>
     */
    public function getAllForMerchant(Merchant $merchant): Collection
    {
        return $merchant->paymentLinks()->latest()->get();
    }

    /**
     * Update a payment link.
     *
     * @param array<string, mixed> $data
     */
    public function update(PaymentLinks $paymentLink, array $data): PaymentLinks
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

        return $paymentLink->fresh();
    }

    /**
     * Delete a payment link.
     */
    public function delete(PaymentLinks $paymentLink): void
    {
        $paymentLink->delete();
    }

    /**
     * Check if payment link is valid for payment.
     *
     * @return array{valid: bool, message?: string}
     */
    public function validateForPayment(PaymentLinks $paymentLink, ?float $amount = null): array
    {
        if (!$paymentLink->isActive()) {
            return [
                'valid' => false,
                'message' => 'Le lien de paiement n\'est pas actif.',
            ];
        }

        if ($paymentLink->isExpired()) {
            return [
                'valid' => false,
                'message' => 'Le lien de paiement a expiré.',
            ];
        }

        if ($paymentLink->hasReachedMaxUses()) {
            return [
                'valid' => false,
                'message' => 'Le lien de paiement a atteint le nombre d\'utilisations maximum.',
            ];
        }

        if (!$paymentLink->amountIsMatching($amount)) {
            return [
                'valid' => false,
                'message' => 'Le montant ne correspond pas au montant requis.',
            ];
        }

        if ($paymentLink->amountIsZeroOrNull($amount)) {
            return [
                'valid' => false,
                'message' => 'Le montant est requis pour ce lien de paiement.',
            ];
        }

        return ['valid' => true];
    }

    /**
     * Increment the uses count.
     */
    public function incrementUses(PaymentLinks $paymentLink): void
    {
        $paymentLink->increment('uses_count');
    }
}
