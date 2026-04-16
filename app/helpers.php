<?php

use Carbon\Carbon;

if (! function_exists('carbon')) {
    function carbon(DateTime|string $dateTime): Carbon
    {
        return Carbon::create($dateTime);
    }
}

if (! function_exists('yesterday')) {
    function yesterday(): Carbon
    {
        return today()->subDay();
    }
}

if (! function_exists('tomorrow')) {
    function tomorrow(): Carbon
    {
        return today()->addDay();
    }
}

if (! function_exists('buildMetadata')) {
    function buildMetadata(array $data = []): array
    {
        return array_merge([
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ], $data);
    }
}

if (! function_exists('roundAmount')) {
    function roundAmount(float $amount): float
    {
        return round($amount, 8);
    }
}

if (! function_exists('format_amount')) {
    /**
     * Format a balance amount with currency device.
     *
     * @param  int  $amount  The amount in coins.
     * @return string The formatted balance with currency device.
     */
    function format_amount(int $amount, string $currency): string
    {
        return number_format($amount, 2, '.', ' ').' '.$currency;
    }
}
