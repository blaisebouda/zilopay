<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\OtpVerification;
use App\Models\User;
use App\Services\Auth\Utils\Identifier;
use Illuminate\Validation\ValidationException;

class OtpVerificationService
{
    public function __construct(private OtpService $otpService) {}

    public function forRegister(array $data): User
    {
        $this->validatOtp($data);

        $user = AuthService::findUserByIdentifier($data['identifier']);

        $identifierType = Identifier::make($data['identifier']);

        $updateData = [];
        if ($identifierType->isEmail()) {
            $updateData['email_verified_at'] = now();
        } elseif ($identifierType->isPhone()) {
            $updateData['phone_verified_at'] = now();
        }

        $user->update($updateData);

        return $user;
    }

    public function resend(array $data): OtpVerification
    {

        $type = $data['type'] ?? 'registration';

        $user = AuthService::findUserByIdentifier($data['identifier']);

        $otp = $this->otpService->resend($data['identifier'], $type, $user);

        return $otp;
    }

    private function validatOtp(array $data): void
    {
        $type = $data['type'] ?? 'registration';

        if (! $this->otpService->verify($data['identifier'], $data['otp_code'], $type)) {
            throw ValidationException::withMessages([
                'otp_code' => 'Code OTP invalide ou expiré.',
            ]);
        }
    }
}
