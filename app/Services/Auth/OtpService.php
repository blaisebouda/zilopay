<?php

namespace App\Services\Auth;

use App\Models\OtpVerification;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * OTP expiration time in minutes.
     */
    protected int $expirationMinutes = 10;

    /**
     * Maximum number of attempts allowed.
     */
    protected int $maxAttempts = 5;

    /**
     * Generate and send OTP.
     */
    public function generate(string $identifier, string $type = 'registration', ?User $user = null): OtpVerification
    {
        // Invalidate any existing active OTPs for this identifier and type
        $this->invalidateExisting($identifier, $type);

        // Generate OTP code
        $otpCode = $this->generateOtpCode();

        // Create OTP record
        $otp = OtpVerification::create([
            'user_id' => $user?->id,
            'identifier' => $identifier,
            'otp_code' => $otpCode,
            'type' => $type,
            'expires_at' => now()->addMinutes($this->expirationMinutes),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Send OTP notification
        $this->sendOtp($identifier, $otpCode, $type, $user);

        Log::info("OTP generated for {$identifier}", [
            'type' => $type,
            'expires_at' => $otp->expires_at,
        ]);

        return $otp;
    }

    /**
     * Verify OTP code.
     */
    public function verify(string $identifier, string $otpCode, string $type = 'registration'): bool
    {
        $otp = OtpVerification::active()
            ->forIdentifier($identifier)
            ->ofType($type)
            ->orderBy('created_at', 'desc')
            ->first();

        if (! $otp) {
            Log::warning("OTP not found for {$identifier}");

            return false;
        }

        // Check if max attempts exceeded
        if ($otp->attempts >= $this->maxAttempts) {
            Log::warning("Max OTP attempts exceeded for {$identifier}");

            return false;
        }

        // Increment attempts
        $otp->incrementAttempts();

        // Verify OTP code
        if ($otp->otp_code !== $otpCode) {
            Log::warning("Invalid OTP code for {$identifier}");

            return false;
        }

        // Mark as verified
        $otp->markAsVerified();

        Log::info("OTP verified successfully for {$identifier}");

        return true;
    }

    /**
     * Invalidate existing OTPs.
     */
    protected function invalidateExisting(string $identifier, string $type): void
    {
        OtpVerification::active()
            ->forIdentifier($identifier)
            ->ofType($type)
            ->update(['is_used' => true]);
    }

    /**
     * Generate OTP code.
     */
    public function generateOtpCode(): string
    {
        // Generate 6-digit OTP
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP via appropriate channel.
     */
    protected function sendOtp(string $identifier, string $otpCode, string $type, ?User $user = null): void
    {
        try {
            // Determine if identifier is email or phone
            if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                $this->sendOtpViaEmail($identifier, $otpCode, $type, $user);
            } else {
                $this->sendOtpViaSms($identifier, $otpCode, $type);
            }
        } catch (\Exception $e) {
            Log::error("Failed to send OTP to {$identifier}: ".$e->getMessage());
        }
    }

    /**
     * Send OTP via email.
     */
    protected function sendOtpViaEmail(string $email, string $otpCode, string $type, ?User $user = null): void
    {
        if ($user) {
            $user->notify(new OtpNotification($otpCode, $type));
        } else {
            // For users not yet registered, we'll log the OTP
            // In production, you'd send an email without a User model
            Log::info("OTP for {$email}: {$otpCode}");
        }
    }

    /**
     * Send OTP via SMS.
     */
    protected function sendOtpViaSms(string $phone, string $otpCode, string $type): void
    {
        // TODO: Integrate with SMS provider (Twilio, Vonage, etc.)
        // For now, just log it
        Log::info("SMS OTP for {$phone}: {$otpCode}");
    }

    /**
     * Resend OTP.
     */
    public function resend(string $identifier, string $type = 'registration', ?User $user = null): OtpVerification
    {
        return $this->generate($identifier, $type, $user);
    }

    /**
     * Check if OTP is valid for identifier.
     */
    public function hasValidOtp(string $identifier, string $type = 'registration'): bool
    {
        return OtpVerification::active()
            ->forIdentifier($identifier)
            ->ofType($type)
            ->exists();
    }

    /**
     * Get remaining time for OTP.
     */
    public function getRemainingTime(string $identifier, string $type = 'registration'): ?int
    {
        $otp = OtpVerification::active()
            ->forIdentifier($identifier)
            ->ofType($type)
            ->orderBy('created_at', 'desc')
            ->first();

        if (! $otp) {
            return null;
        }

        return max(0, $otp->expires_at->diffInSeconds(now()));
    }
}
