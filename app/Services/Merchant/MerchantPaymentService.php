<?php

declare(strict_types=1);

namespace App\Services\Merchant;

use App\Models\Merchant;
use App\Models\MerchantTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MerchantPaymentService
{
    public function __construct(
        private PaymentLinkService $paymentLinkService
    ) {}

    /**
     * Get transaction by UUID.
     *
     * @throws ModelNotFoundException
     */
    public function getByUuid(string $uuid): MerchantTransaction
    {
        return MerchantTransaction::getByUuid($uuid)->firstOrFail();
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
     * Generate a unique reference.
     */
    private function generateReference(): string
    {
        return 'ZPAY_'.strtoupper(uniqid().bin2hex(random_bytes(4)));
    }
}
