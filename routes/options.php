<?php

use Illuminate\Support\Facades\Route;


Route::get('/options', function () {

    return response()->json([
        'contries' => \App\Models\Enums\Country::options(),
        'vault_types' => \App\Models\Enums\VaultType::options(),
    ]);
})->name('options');
