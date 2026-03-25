<?php

namespace App\Models;

use App\Models\Enums\TransactionStatus;
use App\Models\Enums\TransactionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends BaseModel
{
    protected $fillable = [
        'user_id',
        'currency_id',
        'payment_method_id',
        'transaction_type',
        'amount',
        'fee_fixed',
        'fee_percentage',
        'total',
        'status',
        'balance_before',
        'balance_after',

    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'amount' => 'float',
            'fee_fixed' => 'float',
            'fee_percentage' => 'float',
            'total' => 'float',
            'balance_before' => 'float',
            'balance_after' => 'float',
            'status' => TransactionStatus::class,
            'transaction_type' => TransactionType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function deposit(): HasOne
    {
        return $this->hasOne(Deposit::class);
    }

    public function withdrawal(): HasOne
    {
        return $this->hasOne(Withdrawal::class);
    }

    public function transfer(): HasOne
    {
        return $this->hasOne(Transfer::class);
    }

    public function isPending(): bool
    {
        return $this->status->equals(TransactionStatus::PENDING);
    }

    public function isFailed(): bool
    {
        return $this->status->equals(TransactionStatus::FAILED);
    }

    public function isSuccess(): bool
    {
        return $this->status->equals(TransactionStatus::SUCCESS);
    }

    // Scopes
    public function scopeOfType($query, TransactionType $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeWithStatus($query, TransactionStatus $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDeposits($query)
    {
        return $query->where('transaction_type', TransactionType::DEPOSIT);
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('transaction_type', TransactionType::WITHDRAWAL);
    }

    public function scopeTransfers($query)
    {
        return $query->where('transaction_type', TransactionType::TRANSFER);
    }

    public function scopePayments($query)
    {
        return $query->where('transaction_type', TransactionType::PAYMENT);
    }

    public function scopePending($query)
    {
        return $query->where('status', TransactionStatus::PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', TransactionStatus::SUCCESS);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', TransactionStatus::BLOCKED);
    }

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeWhereUuidForType(Builder $query, string $uuid, TransactionType $type): Builder
    {
        return $query->whereUuid($uuid)
            ->where('transaction_type', $type);
    }
}
