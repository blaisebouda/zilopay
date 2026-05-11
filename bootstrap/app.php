<?php

use App\Http\Middleware\MerchantApiKeyMiddleware;
use App\Http\Middleware\MerchantApprovedMiddleware;
use App\Http\Middleware\ValidateSignedPaymentLink;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\ResponseCache\Middlewares\CacheResponse;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->use([
            CacheResponse::class,
        ]);
        $middleware->alias([
            'merchant.approved' => MerchantApprovedMiddleware::class,
            'merchant.api_key' => MerchantApiKeyMiddleware::class,
            'validate.signed.payment.link' => ValidateSignedPaymentLink::class,
            'do.not.cache.response' => DoNotCacheResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
