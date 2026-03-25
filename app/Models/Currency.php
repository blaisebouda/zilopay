<?php

namespace App\Models;

use App\Models\Enums\CurrencyType;
use App\Models\Enums\ModelStatus;
use Illuminate\Database\Eloquent\Builder;

class Currency extends BaseModel
{
    protected $fillable = [
        'type',
        'name',
        'symbol',
        'code',
        'rate',
        'logo',
        'status',
        'default',
        'exchange_from',
        'allow_address_creation',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => CurrencyType::class,
            'status' => ModelStatus::class,
            'default' => 'boolean',
        ];
    }

    protected function scopeDefault(Builder $query)
    {
        return $query->where('default', true);
    }
}
