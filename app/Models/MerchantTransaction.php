<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\MerchantTransactionStatus;
use Database\Factories\MerchantTransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchantTransaction extends Model
{
    /** @use HasFactory<MerchantTransactionFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'merchant_id',
        'uuid',
        'phone_number',
        'gross_amount',
        'platform_fee',
        'net_amount',
        'status',
        'settled_at',
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
            'gross_amount' => 'decimal:8',
            'platform_fee' => 'decimal:8',
            'net_amount' => 'decimal:8',
            'status' => 'integer',
            'settled_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the merchant that owns the transaction.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Check if the transaction is pending.
     */
    public function isPending(): bool
    {
        return $this->status === MerchantTransactionStatus::PENDING->value;
    }

    /**
     * Check if the transaction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === MerchantTransactionStatus::COMPLETED->value;
    }

    /**
     * Check if the transaction is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === MerchantTransactionStatus::FAILED->value;
    }
}
