<?php

namespace App\Services\Merchant;

use App\Models\Enums\MerchantTransactionStatus;
use App\Models\MerchantTransaction;
use App\Models\PaymentLink;
use App\Utils\FeeCalculator;

class ProccesLinkService
{
    public function __construct(
        private PaymentLinkService $paymentLinkService
    ) {}

    public function handle(PaymentLink $paymentLink, array $data): MerchantTransaction
    {
        $validation = $this->paymentLinkService->validateForPayment(
            $paymentLink
        );

        if (! $validation->isValid) {
            throw new \InvalidArgumentException($validation->message);
        }

        $transaction = $this->createTransaction($paymentLink, $data);

        // $this->paymentLinkService->incrementUses($paymentLink);

        return $transaction->refresh();
    }

    private function createTransaction(PaymentLink $paymentLink, array $data): MerchantTransaction
    {
        $merchant = $paymentLink->merchant;
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
}
