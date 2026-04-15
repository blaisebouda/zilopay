<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RegisterStartRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends ApiController
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function register(RegisterRequest $request): JsonResponse|JsonResource
    {
        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'phone_number' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'policy_accepted_at' => $request->policy_accepted ? now() : null,
                'is_verified' => false,
            ]);

            // Generate and send OTP
            $identifier = $user->phone_number ?? $user->email;
            $otp = $this->otpService->generate($identifier, 'registration', $user);

            DB::commit();

            return $this->successResponse([
                'user' => UserResource::make($user->refresh()),
                'otp_expires_in' => $otp->expires_at->diffInSeconds(now()),
            ], 'Inscription réussie.');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function login(LoginRequest $request)
    {
        // Find user by email or phone
        $user = User::where('email', $request->email)
            ->orWhere('phone_number', trim($request->phone_number))
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Identifiants invalides.', 404);
        }

        // Create token
        $tokenName = $request->remember ? 'remember_token' : 'auth_token';
        $token = $user->createToken($tokenName)->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user' => UserResource::make($user),
            'wallet' => WalletResource::make($user->defaultWallet),
        ], 'Connexion réussie.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse([], 'Déconnexion réussie.');
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return $this->successResponse([], 'Déconnexion réussie de tous les appareils.');
    }

    public function me(Request $request): JsonResource
    {
        return UserResource::make($request->user());
    }

    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Delete current token
        $request->user()->currentAccessToken()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(['token' => $token], 'Token rafraîchi avec succès.');
    }
}
