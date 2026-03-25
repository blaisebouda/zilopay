<?php

namespace App\Models;

use App\Models\Enums\ModelStatus;
use App\Models\Enums\PaymentMethodCode;
use App\Models\Enums\PaymentMethodType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentMethod extends Model
{
    protected $fillable = [
        'contry_id',
        'name',
        'logo',
        'type',
        'code',
        'min_amount',
        'max_amount',
        'fee_percent',
        'fee_fixed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'code' => PaymentMethodCode::class,
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'fee_percent' => 'decimal:2',
            'fee_fixed' => 'decimal:2',
            'type' => PaymentMethodType::class,
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function logoUrl()
    {
        return Storage::disk('public')->url(PAYMENT_METHOD_LOGO_PATH . $this->logo);
    }

    public function scopeActive($query)
    {
        return $query->where('status', ModelStatus::ACTIVE);
    }
}
