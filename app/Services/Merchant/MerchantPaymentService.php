<?php

declare(strict_types=1);

namespace App\Services\Merchant;

use App\Models\Enums\MerchantTransactionStatus;
use App\Models\Merchant;
use App\Models\MerchantTransaction;
use App\Models\PaymentLinks;
use App\Utils\FeeCalculator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

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
    public function initiate(Merchant $merchant, array $data): string
    {
        if (! $this->getMerchantApiKey()) {
            throw new \Exception('API key is required !');
        }

        return DB::transaction(function () use ($merchant, $data) {
            $paymentLink = $this->paymentLinkService->create($merchant, $data);

            $transaction = $this->createTransaction($merchant, $data, $paymentLink);

            return $this->generateLink($merchant, $transaction->refresh());
        });
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

        return $transaction->refresh();
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

    private function createTransaction(Merchant $merchant, array $data, PaymentLinks $paymentLink): MerchantTransaction
    {
        $fees = FeeCalculator::make(
            amount: $data['amount'],
            fixedFeedAmount: $merchant->fee_fixed,
            percentageFee: $merchant->fee_percent
        );

        return MerchantTransaction::create([
            'merchant_id' => $merchant->id,
            'payment_link_id' => $paymentLink->id,
            'gross_amount' => $data['amount'],
            'currency' => $data['currency'],
            'status' => MerchantTransactionStatus::PENDING,
            'customer_email' => $data['customer_email'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'amount' => $fees->getAmount(),
            'platform_fee' => $fees->getPercentageFeeAmount(),
            'net_amount' => $fees->getNetAmount(),
            'metadata' => buildMetadata($fees->breakdown()),

        ]);
    }

    /**
     * Get the secure checkout link for a transaction.
     */
    public function getCheckoutLink(MerchantTransaction $transaction): string
    {
        return $this->generateLink($transaction->merchant, $transaction);
    }

    private function generateLink(Merchant $merchant, MerchantTransaction $transaction): string
    {
        // Generate a secure signed URL that redirects to checkout
        $paymentLink = $transaction->paymentLink;

        // Generate a signed URL valid for 30 days
        // Include merchant name and amount in the URL for display purposes
        // These parameters are SIGNED, so they cannot be modified without breaking the signature
        $signedUrl = URL::temporarySignedRoute(
            'merchant.pay',
            now()->addDays(30),
            [
                'merchant_api_key' => $this->getMerchantApiKey(),
                'ref' => $paymentLink->uuid,
                'merchant_name' => $merchant->name,
                'amount' => $transaction->gross_amount,
                'currency' => $transaction->currency,
            ]
        );

        // Build the checkout redirect URL
        $checkoutUrl = config('services.checkout.url');

        // Extract the signed URL path and query string
        $parsedUrl = parse_url($signedUrl);
        $path = $parsedUrl['path'] ?? '';
        $query = $parsedUrl['query'] ?? '';

        // Build the secure checkout link
        $checkoutLink = $checkoutUrl . $path . ($query ? '?' . $query : '');

        return $checkoutLink;
    }

    private function getMerchantApiKey(): ?string
    {
        return request()->attributes->get('merchant_api_key');
    }

    /**
     * Generate a unique reference.
     */
    private function generateReference(): string
    {
        return 'ZPAY_' . strtoupper(uniqid() . bin2hex(random_bytes(4)));
    }
}
