<?php

namespace App\Models;

class Country extends BaseModel
{
    protected $fillable = [
        'short_name',
        'name',
        'flag',
        'iso3',
        'number_code',
        'phone_code',
    ];
}
