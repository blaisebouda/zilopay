<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends ApiController
{

    public function __construct(
        private readonly OtpService $otpService
    ) {}


    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {

            $user = User::where('email', $request->email)
                ->orWhere('phone_number', $request->phone_number)
                ->first();

            // Generate token and OTP
            $token = Str::random(64);
            $identifier = $request->email ?? $request->phone_number;
            $otp = $this->otpService->generate($identifier, 'password_reset', $user);


            // Delete old password reset tokens
            PasswordResetToken::where('email', $request->email)
                ->orWhere('phone_number', $request->phone_number)
                ->delete();

            // Create new password reset token
            PasswordResetToken::create([
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'token' => Hash::make($token),
                'otp_code' => $otp->otp_code,
                'expires_at' => now()->addHour(),
                'ip_address' => $request->ip(),
            ]);



            return $this->successResponse([
                'identifier' => $identifier,
                'expires_at' => now()->addHour()->toIso8601String(),
                'token' => $token,
                'otp_expires_in' => 3600,
            ], 'Un email de réinitialisation a été envoyé.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }



    public function resetPassword(ResetPasswordRequest $request): JsonResource|JsonResponse
    {
        try {
            // Find password reset token
            $resetToken = PasswordResetToken::active()
                ->where('email', $request->email)
                ->orWhere('phone_number', $request->phone_number)
                ->get()
                ->first(function ($token) use ($request) {
                    return Hash::check($request->token, $token->token);
                });

            if (! $resetToken) {
                return $this->errorResponse('Token invalide ou expiré.', 400);
            }

            // Verify OTP
            if ($resetToken->otp_code !== $request->otp_code) {
                $resetToken->incrementAttempts();

                return $this->errorResponse('Code OTP invalide.', 400);
            }

            // Update password
            $user = User::where('email', $request->email)->first();
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Mark token as used
            $resetToken->markAsUsed();

            // Logout user
            $this->logoutUser($request, $user);

            return $this->successResponse(new JsonResource([]), 'Mot de passe réinitialisé avec succès.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    private function logoutUser(Request $request)
    {
        if ($request->hasSession() || $request->header('X-Inertia')) {
            auth()->guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->successResponse([], 'Déconnexion réussie.');
        }

        $request->user()?->currentAccessToken()?->delete();
    }
}
