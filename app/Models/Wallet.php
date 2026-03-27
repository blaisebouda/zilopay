<?php

namespace App\Models;

use App\Models\Enums\CommonStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'currency',
        'balance',
        'is_default',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'balance' => 'float',
            'is_default' => 'boolean',
            'status' => CommonStatus::class,
            'currency' => \App\Models\Enums\Currency::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    public function hasInsufficientBalance(float $amount): bool
    {
        return $this->balance < $amount;
    }

    public function credit(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

    public function debit(float $amount): void
    {
        if ($this->hasInsufficientBalance($amount)) {
            throw new \Exception('Insufficient balance');
        }
        $this->balance -= $amount;
        $this->save();
    }

    public function generateCode(): string
    {
        return "ZP" . self::generateUniqueCode('code', 8);
    }

    public static function getDefaultForUser(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('is_default', true)
            ->first();
    }

    protected static function booted(): void
    {
        static::creating(function (Wallet $wallet) {
            $wallet->code = $wallet->generateCode();
        });
    }
}
