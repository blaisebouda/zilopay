<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Enums\MerchantStatus;
use App\Models\Merchant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MerchantApprovedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $merchant = Merchant::where('user_id', $user->id)->first();

        if (! $merchant) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'No merchant profile found.',
            ], 403);
        }

        if ($merchant->status !== MerchantStatus::APPROVED->value) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'Merchant account is not approved.',
                'data' => [
                    'status' => $merchant->status,
                    'status_label' => MerchantStatus::from($merchant->status)->label(),
                ],
            ], 403);
        }

        $request->attributes->set('merchant', $merchant);

        return $next($request);
    }
}
