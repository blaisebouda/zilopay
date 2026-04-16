<?php

use App\Models\Enums\Country;
use App\Models\Enums\VaultType;
use Illuminate\Support\Facades\Route;

Route::get('/options', function () {

    return response()->json([
        'contries' => Country::options(),
        'vault_types' => VaultType::options(),
    ]);
})->name('options');
