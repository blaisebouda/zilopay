<?php

namespace App\Models;

use App\Models\Enums\VaultTransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaultTransaction extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'vault_id',
        'uuid',
        'amount',
        'type',
        'description',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'type' => VaultTransactionType::class,
            'metadata' => 'array',
        ];
    }

    public function vault(): BelongsTo
    {
        return $this->belongsTo(Vault::class);
    }

    public function isDeposit(): bool
    {
        return $this->type->equals(VaultTransactionType::DEPOSIT);
    }

    public function isWithdrawal(): bool
    {
        return $this->type->equals(VaultTransactionType::WITHDRAWAL);
    }
}
