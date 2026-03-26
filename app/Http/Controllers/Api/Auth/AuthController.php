<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\OtpService;
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
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'account_type' => $request->account_type,
                'guardian_for' => $request->guardian_for,
                'policy' => $request->islamic_charter_accepted ? now() : null,
                'is_verified' => false,
            ]);

            // Generate and send OTP
            $otp = $this->otpService->generate($user->phone, 'registration', $user);

            DB::commit();

            return $this->successResourceResponse(UserResource::make($user)->additional([
                'otp_expires_in' => $otp->expires_at->diffInSeconds(now()),
            ]), 'Inscription réussie. Un code OTP a été envoyé à votre numéro de téléphone.');
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        // Find user by email or phone
        $user = User::where('email', $request->identifier)
            // ->orWhere('phone', $request->identifier)
            ->first();


        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Identifiants invalides.', 404);
        }

        // // Check if user is active
        // if (! $user->is_active) {
        //     return $this->errorResponse('Votre compte a été désactivé.', 403);
        // }

        // Create token
        $tokenName = $request->remember ? 'remember_token' : 'auth_token';
        $token = $user->createToken($tokenName)->plainTextToken;

        return $this->successResourceResponse(UserResource::make($user->refresh())->additional([
            'token' => $token,
        ]), 'Connexion réussie.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse([], 'Déconnexion réussie.');
    }

    public function logoutAll(Request $request): JsonResource
    {
        $request->user()->tokens()->delete();

        return $this->successResourceResponse(new JsonResource([]), 'Déconnexion réussie de tous les appareils.');
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
