<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Services\Auth\OtpService;
use App\Services\Auth\OtpVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class OtpController extends ApiController
{

    public function __construct(private OtpVerificationService $otpVerificationService) {}

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        try {

            $user = $this->otpVerificationService->forRegister($request->validated());

            return $this->successResponse([
                'message' => 'Vérification réussie.',
                'user' => $user,
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Failed to verify OTP', [
                'error' => $e->getMessage(),
                'identifier' => $request->identifier,
                'type' => $request->type,
            ]);

            return $this->errorResponse('Échec de la vérification', 500);
        }
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {

        try {
            $otp = $this->otpVerificationService->resend($request->validated());
            return $this->successResponse(
                [
                    'expires_at' => $otp->expires_at->toISOString(),
                    'otp_expires_in' => abs($otp->expires_at->diffInSeconds(now())),
                ],
                'Un nouveau code OTP a été envoyé.',

            );
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP', [
                'error' => $e->getMessage(),
                'identifier' => $request->identifier,
                'type' => $request->type,
            ]);

            return $this->errorResponse('Échec de l\'envoi du code OTP', 500);
        }
    }
}
