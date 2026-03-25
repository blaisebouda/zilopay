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
