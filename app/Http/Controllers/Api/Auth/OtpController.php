<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class OtpController extends ApiController
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $type = $request->type ?? 'registration';

        if (! $this->otpService->verify($request->identifier, $request->otp_code, $type)) {
            return response()->json([
                'message' => 'Code OTP invalide ou expiré.',
            ], 400);
        }

        // Find user by identifier
        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (! $user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        // Update user verification status
        if ($type === 'registration') {
            $user->update([
                'email_verified_at' => now(),
                'is_verified' => true,
                'verification_status' => 'email_verified',
            ]);
        } elseif ($type === 'phone_verification') {
            $user->update([
                'phone_verified_at' => now(),
                'verification_status' => $user->email_verified_at ? 'fully_verified' : 'phone_verified',
            ]);
        }

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Vérification réussie.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'identifier' => 'required|string',
            'type' => 'nullable|string|in:registration,login,password_reset,phone_verification',
        ]);

        $type = $request->type ?? 'registration';

        // Find user
        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        $otp = $this->otpService->resend($request->identifier, $type, $user);

        return response()->json([
            'message' => 'Un nouveau code OTP a été envoyé.',
            'otp_expires_in' => $otp->expires_at->diffInSeconds(now()),
        ]);
    }
}
