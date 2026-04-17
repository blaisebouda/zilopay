<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\Country;
use App\Models\Enums\Currency;
use App\Models\Enums\MerchantStatus;
use App\Models\Traits\HasFeed;
use Database\Factories\MerchantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merchant extends BaseModel
{
    /** @use HasFactory<MerchantFactory> */
    use HasFactory, HasFeed;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'business_name',
        'business_email',
        'phone_number',
        'country',
        'currency',
        'fee_fixed',
        'fee_percent',
        'status',
        'approved_at',
        'approved_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => MerchantStatus::class,
            'fee_fixed' => 'decimal:8',
            'fee_percent' => 'decimal:2',
            'approved_at' => 'datetime',
            'country' => Country::class,
            'currency' => Currency::class,
        ];
    }

    /**
     * Get the user that owns the merchant profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment links for the merchant.
     */
    public function paymentLinks(): HasMany
    {
        return $this->hasMany(PaymentLinks::class);
    }

    /**
     * Get the API keys for the merchant.
     */
    public function apiKeys(): HasMany
    {
        return $this->hasMany(MerchantApiKey::class);
    }

    /**
     * Get the transactions for the merchant.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(MerchantTransaction::class);
    }

    /**
     * Get the documents for the merchant.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(MerchantDocument::class);
    }

    /**
     * Get the approver user.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if the merchant is approved.
     */
    public function isApproved(): bool
    {
        return $this->status->equals(MerchantStatus::APPROVED);
    }

    /**
     * Check if the merchant is pending.
     */
    public function isPending(): bool
    {
        return $this->status->equals(MerchantStatus::PENDING);
    }
}
