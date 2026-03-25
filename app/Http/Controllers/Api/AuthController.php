<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\{
    ForgotPasswordRequest,
    LoginRequest,
    RegisterRequest,
    ResetPasswordRequest,
    VerifyOtpRequest
};
use App\Http\Resources\UserResource;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends ApiController
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Authentication"},
     *     summary="Inscription d'un nouvel utilisateur",
     *     description="Crée un nouveau compte utilisateur et envoie un OTP pour vérification",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={ "phone", "password", "password_confirmation", "account_type", "islamic_charter_accepted"},
     *
     *             @OA\Property(property="phone", type="string", example="+221771234567"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123!"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="Password123!"),
     *             @OA\Property(property="account_type", type="string", enum={"personal", "guardian", "admin", "moderator"}, example="personal"),
     *             @OA\Property(property="guardian_for", type="integer", nullable=true, example=null),
     *             @OA\Property(property="islamic_charter_accepted", type="boolean", example=true)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé avec succès, OTP envoyé",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Inscription réussie. Un code OTP a été envoyé à votre numéro de téléphone."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="email", type="string", example="ahmed@example.com"),
     *                 @OA\Property(property="phone", type="string", example="+221771234567")
     *             ),
     *             @OA\Property(property="otp_expires_in", type="integer", example=600)
     *         )
     *     ),
     *
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
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

            return self::successJson(UserResource::make($user)->additional([
                'otp_expires_in' => $otp->expires_at->diffInSeconds(now()),
            ]), 'Inscription réussie. Un code OTP a été envoyé à votre numéro de téléphone.');
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/auth/verify-otp",
     *     tags={"Authentication"},
     *     summary="Vérifier le code OTP",
     *     description="Vérifie le code OTP et active le compte utilisateur",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"identifier", "otp_code"},
     *
     *             @OA\Property(property="identifier", type="string", example="ahmed@example.com"),
     *             @OA\Property(property="otp_code", type="string", example="123456"),
     *             @OA\Property(property="type", type="string", enum={"registration", "login", "password_reset", "phone_verification"}, example="registration")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OTP vérifié avec succès",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Compte vérifié avec succès."),
     *             @OA\Property(property="token", type="string", example="1|abcdef..."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(response=400, description="Code OTP invalide ou expiré")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/auth/resend-otp",
     *     tags={"Authentication"},
     *     summary="Renvoyer le code OTP",
     *     description="Renvoie un nouveau code OTP",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"identifier"},
     *
     *             @OA\Property(property="identifier", type="string", example="ahmed@example.com"),
     *             @OA\Property(property="type", type="string", enum={"registration", "login", "password_reset", "phone_verification"}, example="registration")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OTP renvoyé avec succès",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Un nouveau code OTP a été envoyé."),
     *             @OA\Property(property="otp_expires_in", type="integer", example=600)
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Authentication"},
     *     summary="Connexion utilisateur",
     *     description="Authentifie un utilisateur et retourne un token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"identifier", "password"},
     *
     *             @OA\Property(property="identifier", type="string", example="user@unionhalal.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="remember", type="boolean", example=false)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Connexion réussie."),
     *             @OA\Property(property="token", type="string", example="1|abcdef..."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(response=401, description="Identifiants invalides")
     * )
     */
    public function login(LoginRequest $request): JsonResource|JsonResponse
    {
        // Find user by email or phone
        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return self::errorJson('Identifiants invalides.', 404);
        }

        // Check if user is active
        if (! $user->is_active) {
            return self::errorJson('Votre compte a été désactivé.', 403);
        }

        // Check if Islamic charter is accepted
        if (! $user->hasAcceptedCharter()) {
            return self::errorJson('Vous devez accepter la charte islamique pour continuer.', 403);
        }


        // Create token
        $tokenName = $request->remember ? 'remember_token' : 'auth_token';
        $token = $user->createToken($tokenName)->plainTextToken;

        return self::successJson(UserResource::make($user->refresh())->additional([
            'token' => $token,
        ]), 'Connexion réussie.');
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     tags={"Authentication"},
     *     summary="Déconnexion utilisateur",
     *     description="Révoque le token d'authentification actuel",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Déconnexion réussie.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResource
    {
        $request->user()->currentAccessToken()->delete();

        return self::successJson(new JsonResource([]), 'Déconnexion réussie.');
    }

    /**
     * @OA\Post(
     *     path="/auth/logout-all",
     *     tags={"Authentication"},
     *     summary="Déconnexion de tous les appareils",
     *     description="Révoque tous les tokens d'authentification de l'utilisateur",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie de tous les appareils",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Déconnexion réussie de tous les appareils.")
     *         )
     *     )
     * )
     */
    public function logoutAll(Request $request): JsonResource
    {
        $request->user()->tokens()->delete();

        return self::successJson(new JsonResource([]), 'Déconnexion réussie de tous les appareils.');
    }

    /**
     * @OA\Get(
     *     path="/auth/me",
     *     tags={"Authentication"},
     *     summary="Obtenir l'utilisateur connecté",
     *     description="Retourne les informations de l'utilisateur authentifié",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Informations utilisateur",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="user", type="object")
     *         )
     *     )
     * )
     */
    public function me(Request $request): JsonResource
    {
        return UserResource::make($request->user()->load(['profile', 'wallet']));
    }

    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     tags={"Authentication"},
     *     summary="Rafraîchir le token",
     *     description="Révoque le token actuel et en crée un nouveau",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Token rafraîchi avec succès",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Token rafraîchi avec succès."),
     *             @OA\Property(property="token", type="string", example="2|ghijkl...")
     *         )
     *     )
     * )
     */
    public function refresh(Request $request): JsonResource
    {
        $user = $request->user();

        // Delete current token
        $request->user()->currentAccessToken()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return self::successJson(new JsonResource(['token' => $token]), 'Token rafraîchi avec succès.');
    }

    /**
     * @OA\Post(
     *     path="/auth/forgot-password",
     *     tags={"Authentication"},
     *     summary="Demande de réinitialisation de mot de passe",
     *     description="Envoie un email avec un token et un OTP pour réinitialiser le mot de passe",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="ahmed@example.com")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Email de réinitialisation envoyé",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Un email de réinitialisation a été envoyé.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/auth/reset-password",
     *     tags={"Authentication"},
     *     summary="Réinitialiser le mot de passe",
     *     description="Réinitialise le mot de passe avec le token et l'OTP",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"token", "email", "otp_code", "password", "password_confirmation"},
     *
     *             @OA\Property(property="token", type="string", example="abcdef123456..."),
     *             @OA\Property(property="email", type="string", format="email", example="ahmed@example.com"),
     *             @OA\Property(property="otp_code", type="string", example="123456"),
     *             @OA\Property(property="password", type="string", format="password", example="NewPassword123!"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="NewPassword123!")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe réinitialisé avec succès",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Mot de passe réinitialisé avec succès.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/auth/accept-charter",
     *     tags={"Authentication"},
     *     summary="Accepter la charte islamique",
     *     description="Accepte la charte islamique pour l'utilisateur connecté",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Charte acceptée avec succès",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Charte islamique acceptée avec succès.")
     *         )
     *     )
     * )
     */
    public function acceptCharter(Request $request): JsonResource
    {
        $user = $request->user();
        $user->acceptCharter();

        return self::successJson(UserResource::make($user), 'Charte islamique acceptée avec succès.');
    }
}
