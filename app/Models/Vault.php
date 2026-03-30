<?php

namespace App\Models;

use App\Models\Enums\Currency;
use App\Models\Enums\VaultStatus;
use App\Models\Enums\VaultType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vault extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'uuid',
        'name',
        'description',
        'amount',
        'currency',
        'type',
        'status',
        'maturity_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'type' => VaultType::class,
            'status' => VaultStatus::class,
            'currency' => Currency::class,
            'maturity_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(VaultTransaction::class);
    }

    public function isLocked(): bool
    {
        return $this->status->equals(VaultStatus::LOCKED);
    }

    public function isActive(): bool
    {
        return $this->status->equals(VaultStatus::ACTIVE);
    }

    public function hasSufficientBalance(float $amount): bool
    {
        return $this->amount >= $amount;
    }

    public function credit(float $amount): void
    {
        $this->amount += $amount;
        $this->save();
    }

    public function debit(float $amount): void
    {
        if (! $this->hasSufficientBalance($amount)) {
            throw new \InvalidArgumentException('Solde insuffisant dans le coffre-fort');
        }
        $this->amount -= $amount;
        $this->save();
    }

    public function toggleLock(): void
    {
        $this->status = $this->isLocked() ? VaultStatus::ACTIVE : VaultStatus::LOCKED;
        $this->save();
    }
}
