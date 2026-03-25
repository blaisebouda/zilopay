<?php

namespace App\Models;

use App\Models\Enums\OtpType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'identifier',
        'otp_code',
        'type',
        'expires_at',
        'verified_at',
        'attempts',
        'is_used',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_used' => 'boolean',
        'attempts' => 'integer',
        'type' => OtpType::class
    ];

    /**
     * Get the user that owns the OTP verification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the OTP is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the OTP is valid.
     */
    public function isValid(): bool
    {
        return ! $this->is_used && ! $this->isExpired() && $this->attempts < 5;
    }

    /**
     * Mark the OTP as verified.
     */
    public function markAsVerified(): bool
    {
        return $this->update([
            'verified_at' => now(),
            'is_used' => true,
        ]);
    }

    /**
     * Increment the attempts counter.
     */
    public function incrementAttempts(): bool
    {
        return $this->increment('attempts');
    }

    /**
     * Scope to get active OTPs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_used', false)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope to get OTPs by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get OTPs by identifier.
     */
    public function scopeForIdentifier($query, string $identifier)
    {
        return $query->where('identifier', $identifier);
    }
}
