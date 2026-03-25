<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'otp_code',
        'expires_at',
        'is_used',
        'attempts',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
        'attempts' => 'integer',
    ];

    /**
     * Check if the token is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the token is valid.
     */
    public function isValid(): bool
    {
        return ! $this->is_used && ! $this->isExpired() && $this->attempts < 5;
    }

    /**
     * Mark the token as used.
     */
    public function markAsUsed(): bool
    {
        return $this->update(['is_used' => true]);
    }

    /**
     * Increment the attempts counter.
     */
    public function incrementAttempts(): bool
    {
        return $this->increment('attempts');
    }

    /**
     * Scope to get active tokens.
     */
    public function scopeActive($query)
    {
        return $query->where('is_used', false)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope to get tokens by email.
     */
    public function scopeForEmail($query, string $email)
    {
        return $query->where('email', $email);
    }
}
