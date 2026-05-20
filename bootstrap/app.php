<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\MerchantApiKeyMiddleware;
use App\Http\Middleware\MerchantApprovedMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Spatie\ResponseCache\Middlewares\CacheResponse;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            // CacheResponse::class, // TODO: Enable this when is production ready
        ]);
        $middleware->statefulApi();

        $middleware->prepend(HandleCors::class);

        $middleware->alias([
            'merchant.approved' => MerchantApprovedMiddleware::class,
            'merchant.api_key' => MerchantApiKeyMiddleware::class,
            'do.not.cache.response' => DoNotCacheResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
