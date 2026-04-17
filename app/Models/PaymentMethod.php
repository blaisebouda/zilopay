<?php

namespace App\Models;

use App\Models\Enums\Country;
use App\Models\Enums\Currency;
use App\Models\Enums\LockActiveStatus;
use App\Models\Enums\PaymentMethodCode;
use App\Models\Enums\PaymentMethodType;
use App\Models\Traits\HasFeed;
use App\Models\Traits\HasLockActiveStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentMethod extends Model
{
    use HasLockActiveStatus, HasFeed;

    protected $fillable = [
        'country',
        'name',
        'logo',
        'type',
        'code',
        'min_amount',
        'max_amount',
        'fee_fixed',
        'fee_percent',
        'currency',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'country' => Country::class,
            'code' => PaymentMethodCode::class,
            'min_amount' => 'float',
            'max_amount' => 'float',
            'fee_percent' => 'float',
            'fee_fixed' => 'float',
            'currency' => Currency::class,
            'type' => PaymentMethodType::class,
            'status' => LockActiveStatus::class,
        ];
    }

    public function logoUrl()
    {
        return Storage::disk('public')->url($this->logo);
    }

    public function minAmountLabel()
    {
        return format_amount($this->min_amount, $this->currency->symbol());
    }

    public function maxAmountLabel()
    {
        return format_amount($this->max_amount, $this->currency->symbol());
    }


    public function amountRangeLabel()
    {
        return $this->minAmountLabel() . ' - ' . $this->maxAmountLabel();
    }

    public function scopeActive($query)
    {
        return $query->where('status', LockActiveStatus::ACTIVE);
    }
}
