<?php

declare(strict_types=1);

namespace App\Services\Merchant;

use App\Models\Enums\MerchantStatus;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MerchantService
{
    /**
     * Create a new merchant profile.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(User $user, array $data): Merchant
    {
        $existingMerchant = Merchant::where('user_id', $user->id)->first();

        if ($existingMerchant) {
            throw new \InvalidArgumentException('L\'utilisateur a déjà un profil marchand.');
        }

        $merchant = Merchant::create([
            'user_id' => $user->id,
            'business_name' => $data['business_name'],
            'business_email' => $data['business_email'],
            'phone' => $data['phone'] ?? null,
            'country' => $data['country'],
            'fee_fixed' => 0,
            'fee_percentage' => 0,
            'status' => MerchantStatus::PENDING->value,
        ]);

        return $merchant->refresh();
    }

    /**
     * Get merchant by UUID.
     *
     * @throws ModelNotFoundException
     */
    public function getByUuid(string $uuid): Merchant
    {
        return Merchant::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Check if merchant is approved.
     */
    public function isApproved(Merchant $merchant): bool
    {
        return $merchant->status === MerchantStatus::APPROVED->value;
    }

    /**
     * Get merchant statistics.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(Merchant $merchant): array
    {
        $recentPayments = $merchant->transactions()
            ->latest()
            ->limit(5)
            ->get();

        $totalRevenue = $merchant->transactions()
            ->where('status', 'completed')
            ->sum('amount');

        $pendingPayments = $merchant->transactions()
            ->where('status', 'pending')
            ->count();

        $paymentLinksCount = $merchant->paymentLinks()->count();
        $activePaymentLinksCount = $merchant->paymentLinks()
            ->where('status', 1)
            ->count();

        return [
            'merchant' => $merchant,
            'recent_payments' => $recentPayments,
            'total_revenue' => $totalRevenue,
            'pending_payments_count' => $pendingPayments,
            'payment_links_count' => $paymentLinksCount,
            'active_payment_links_count' => $activePaymentLinksCount,
        ];
    }
}
