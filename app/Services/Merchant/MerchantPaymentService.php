<?php

declare(strict_types=1);

namespace App\Services\Merchant;

use App\Models\Enums\MerchantTransactionStatus;
use App\Models\Merchant;
use App\Models\MerchantTransaction;
use App\Models\PaymentLinks;
use App\Services\Merchant\Utils\MerchantFeeCalculator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MerchantPaymentService
{
    public function __construct(
        private PaymentLinkService $paymentLinkService
    ) {}

    /**
     * Initiate a payment via API.
     *
     * @param  array<string, mixed>  $data
     */
    public function initiate(Merchant $merchant, array $data): MerchantTransaction
    {

        $fees = MerchantFeeCalculator::calculate($data['amount'], $merchant);

        $transaction = MerchantTransaction::create([
            'merchant_id' => $merchant->id,
            'gross_amount' => $data['amount'],
            'currency' => $data['currency'],
            'status' => MerchantTransactionStatus::PENDING,
            'customer_email' => $data['customer_email'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'metadata' => buildMetadata(['description' => $data['description'] ?? null]),
        ]);

        return $transaction->fresh();
    }

    /**
     * Process a payment via payment link.
     *
     * @param  array<string, mixed>  $data
     */
    public function processViaLink(PaymentLinks $paymentLink, array $data): MerchantTransaction
    {
        $validation = $this->paymentLinkService->validateForPayment(
            $paymentLink,
            $data['amount'] ?? null
        );

        if (! $validation['valid']) {
            throw new \InvalidArgumentException($validation['message']);
        }

        $amount = $paymentLink->amount ?? $data['amount'];

        $transaction = new MerchantTransaction;
        $transaction->merchant_id = $paymentLink->merchant_id;
        $transaction->payment_link_id = $paymentLink->id;
        $transaction->amount = $amount;
        $transaction->currency = $paymentLink->currency;
        $transaction->status = 'pending';
        $transaction->customer_email = $data['customer_email'] ?? null;
        $transaction->customer_phone = $data['customer_phone'] ?? null;
        $transaction->customer_name = $data['customer_name'] ?? null;
        $transaction->reference = $this->generateReference();
        $transaction->metadata = $data['metadata'] ?? null;
        $transaction->save();

        $this->paymentLinkService->incrementUses($paymentLink);

        return $transaction->fresh();
    }

    /**
     * Get transaction by UUID.
     *
     * @throws ModelNotFoundException
     */
    public function getByUuid(string $uuid): MerchantTransaction
    {
        return MerchantTransaction::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Get transactions for a merchant.
     *
     * @return Collection<int, MerchantTransaction>
     */
    public function getForMerchant(Merchant $merchant, array $filters = []): Collection
    {
        $query = $merchant->transactions();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['from_date'])) {
            $query->where('created_at', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('created_at', '<=', $filters['to_date']);
        }

        return $query->latest()->get();
    }

    /**
     * Calculate fees for a transaction.
     *
     * @return array<string, mixed>
     */
    public function calculateFees(float $amount, Merchant $merchant): array
    {
        return MerchantFeeCalculator::getFeeBreakdown($amount, $merchant);
    }

    /**
     * Generate a unique reference.
     */
    private function generateReference(): string
    {
        return 'ZPAY_'.strtoupper(uniqid().bin2hex(random_bytes(4)));
    }
}
