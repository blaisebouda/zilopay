<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSignedPaymentLink
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if signature is valid for public payment link access
        // This allows both signed URLs (secure) and regular URLs for backward compatibility
        // For production, you should enforce signed URLs only

        if ($request->hasValidSignature(false)) {
            // Request has valid signature, proceed
            return $next($request);
        }

        // Check if signature is required (configurable)
        if (config('services.checkout.require_signed_url', false)) {
            return response()->json([
                'message' => 'Invalid or missing signature',
                'error' => 'INVALID_SIGNATURE',
            ], 403);
        }

        // Signature not required, allow access
        return $next($request);
    }
}
