<?php

namespace App\Models;

use App\Models\Enums\CommonStatus;
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
        'fee_fixed',
        'fee_percent',
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
            'min_amount' => 'float',
            'max_amount' => 'float',
            'fee_percent' => 'float',
            'fee_fixed' => 'float',
            'type' => PaymentMethodType::class,
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function logoUrl()
    {
        return Storage::disk('public')->url(PAYMENT_METHOD_LOGO_PATH.$this->logo);
    }

    public function scopeActive($query)
    {
        return $query->where('status', CommonStatus::ACTIVE);
    }
}
