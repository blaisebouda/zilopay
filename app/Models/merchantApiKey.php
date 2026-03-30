<?php

namespace App\Models;

use Database\Factories\MerchantApiKeyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class merchantApiKey extends Model
{
    /** @use HasFactory<MerchantApiKeyFactory> */
    use HasFactory;
}
