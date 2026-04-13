<?php

declare(strict_types=1);

namespace App\Services\Merchant;

use App\Models\Enums\DocumentStatus;
use App\Models\Enums\DocumentType;
use App\Models\Enums\MerchantStatus;
use App\Models\Enums\PaymentLinkStatus;
use App\Models\Merchant;
use App\Models\MerchantDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MerchantService
{
    /**
     * Create a new merchant profile.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(User $user, array $data): Merchant
    {
        // $existingMerchant = Merchant::where('user_id', $user->id)->first();

        // if ($existingMerchant) {
        //     throw new \InvalidArgumentException('L\'utilisateur a déjà un profil marchand.');
        // }

        return DB::transaction(function () use ($user, $data) {
            $merchant = Merchant::create([
                'user_id' => $user->id,
                'business_name' => $data['business_name'],
                'business_email' => $data['business_email'],
                'phone_number' => $data['phone_number'] ?? null,
                'country' => $data['country'],
                'fee_fixed' => 0,
                'fee_percentage' => 0,
                'status' => MerchantStatus::PENDING,
            ]);

            // Handle document uploads
            if (!empty($data['documents'])) {
                $this->uploadDocuments($merchant, $data['documents']);
            }

            return $merchant->refresh();
        });
    }

    /**
     * Upload merchant documents.
     *
     * @param  array<string, UploadedFile>  $documents
     */
    private function uploadDocuments(Merchant $merchant, array $documents): void
    {
        foreach ($documents as $type => $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->store(MERCHANT_DOCUMENTS_PATH . $merchant->id, 'local');

                MerchantDocument::create([
                    'merchant_id' => $merchant->id,
                    'type' => DocumentType::BUSINESS_LICENSE,
                    'path' => $path,
                    'status' => DocumentStatus::PENDING,
                ]);
            }
        }
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
        return $merchant->status === MerchantStatus::APPROVED;
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
            ->where('status', PaymentLinkStatus::ACTIVE->value)
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
