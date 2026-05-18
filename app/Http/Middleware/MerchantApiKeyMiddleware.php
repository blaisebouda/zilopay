<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\MerchantApiKey;
use App\Services\Merchant\Utils\ApiKeyHasher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MerchantApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        $apiSecret = $request->header('X-API-Secret');

        if (! $apiKey || ! $apiSecret) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'message' => 'API credentials missing. X-API-Key and X-API-Secret headers are required.',
            ], 401);
        }


        $merchantApiKey = MerchantApiKey::active($apiKey)->first();

        if (! $merchantApiKey) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'message' => 'Invalid or expired API key.',
            ], 401);
        }

        if (! ApiKeyHasher::verifySecret($apiSecret, $merchantApiKey->secret)) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'message' => 'Invalid API secret.',
            ], 401);
        }

        $merchantApiKey->update(['last_used_at' => now()]);

        $request->attributes->set('merchant', $merchantApiKey->merchant);
        $request->attributes->set('merchant_api_key', $merchantApiKey);

        return $next($request);
    }
}
