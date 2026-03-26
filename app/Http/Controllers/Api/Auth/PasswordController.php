<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PasswordController extends ApiController
{
    public function forgotPassword(ForgotPasswordRequest $request): JsonResource
    {
        $user = User::where('email', $request->email)->first();

        // Generate token and OTP
        $token = Str::random(64);
        $otpCode = $this->otpService->generateOtpCode();

        // Delete old password reset tokens
        PasswordResetToken::where('email', $request->email)->delete();

        // Create new password reset token
        PasswordResetToken::create([
            'email' => $request->email,
            'token' => Hash::make($token),
            'otp_code' => $otpCode,
            'expires_at' => now()->addHour(),
            'ip_address' => $request->ip(),
        ]);

        // Send notification
        $user->notify(new PasswordResetNotification($token, $otpCode));

        return self::successJson(new JsonResource([]), 'Un email de réinitialisation a été envoyé.');
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResource|JsonResponse
    {
        // Find password reset token
        $resetToken = PasswordResetToken::active()
            ->forEmail($request->email)
            ->get()
            ->first(function ($token) use ($request) {
                return Hash::check($request->token, $token->token);
            });

        if (! $resetToken) {
            return self::errorJson('Token invalide ou expiré.', 400);
        }

        // Verify OTP
        if ($resetToken->otp_code !== $request->otp_code) {
            $resetToken->incrementAttempts();

            return self::errorJson('Code OTP invalide.', 400);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Mark token as used
        $resetToken->markAsUsed();

        // Revoke all tokens
        $user->tokens()->delete();

        return self::successJson(new JsonResource([]), 'Mot de passe réinitialisé avec succès.');
    }
}
