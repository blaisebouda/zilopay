<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\DocumentStatus;
use App\Models\Enums\DocumentType;
use Database\Factories\MerchantDocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchantDocument extends Model
{
    /** @use HasFactory<MerchantDocumentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'merchant_id',
        'type',
        'path',
        'status',
        'rejection_reason',
        'verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => DocumentType::class,
            'status' => DocumentStatus::class,
            'verified_at' => 'datetime',
        ];
    }

    public function getUrlAttribute(): string
    {
        return route('merchant.documents.download', ['path' => $this->path]);
    }

    /**
     * Get the merchant that owns the document.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
