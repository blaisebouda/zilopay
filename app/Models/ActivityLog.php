<?php

namespace App\Models;

use App\Models\Enums\ActivityLogAction;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',

        'description',
        'data',
        'actor_id',
        'action',

        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'action' => ActivityLogAction::class,

        ];
    }
}
