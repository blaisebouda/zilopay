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
     * @param  float  $amount  The amount in coins.
     * @return string The formatted balance with currency device.
     */
    function format_amount(float $amount, string $currency = DEFAULT_CURRENCY): string
    {
        return number_format($amount, 0, '.', ' ') . ' ' . $currency;
    }
}

if (! function_exists('buildPath')) {

    function buildPath(...$path): string
    {
        return implode('/', $path) . '/';
    }
}


if (! function_exists('formatBigNumber')) {
    /**
     * Format a number according to it's size.
     *
     * @param  int  $number  The number to format.
     * @return string The formatted number.
     */
    function formatBigNumber(int $number): string
    {
        $units = ['', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];
        $power = floor(log($number, 1000));
        $formattedNumber = number_format($number / pow(1000, $power), 1, '.', ',') . $units[$power];

        return $formattedNumber;
    }
}
