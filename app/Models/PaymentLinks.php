<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\LockActiveStatus;
use App\Models\Enums\Currency;
use Database\Factories\PaymentLinksFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentLinks extends BaseModel
{
    /** @use HasFactory<PaymentLinksFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'merchant_id',
        'title',
        'description',
        'amount',
        'currency',
        'status',
        'max_uses',
        'uses_count',
        'expires_at',
        'metadata',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'status' => LockActiveStatus::class,
            'currency' => Currency::class,
            'max_uses' => 'integer',
            'uses_count' => 'integer',
            'expires_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the merchant that owns the payment link.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Get the transactions associated with this payment link.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(MerchantTransaction::class, 'payment_link_id');
    }

    /**
     * Check if the payment link has expired.
     */
    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return false;
        }

        return now()->greaterThan($this->expires_at);
    }

    /**
     * Check if the payment link has reached max uses.
     */
    public function hasReachedMaxUses(): bool
    {
        if ($this->max_uses === null) {
            return false;
        }

        return $this->uses_count >= $this->max_uses;
    }

    public function isInactive(): bool
    {
        return $this->status->equals(LockActiveStatus::INACTIVE);
    }

    public function amountIsMatching(?float $amount): bool
    {
        if ($this->amount === null || $amount === null) {
            return false;
        }

        return $amount === $this->amount;
    }

    public function amountIsZeroOrNull(?float $amount): bool
    {
        return $this->amount === null && ($amount === null || $amount <= 0);
    }
}
