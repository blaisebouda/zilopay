<?php

namespace App\Models;

use App\Models\Enums\ModelStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency_id',
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
            'status' => ModelStatus::class,
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

    public static function getDefaultForUser(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('is_default', true)
            ->first();
    }
}
