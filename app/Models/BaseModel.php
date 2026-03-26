<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasFactory;

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Generate a unique code.
     */
    public static function generateUniqueCode(string $column, int $length = 10): string
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (self::where($column, $code)->exists());

        return $code;
    }

    /**
     * Scope a query to only include transactions for a specific user.
     */
    // public function scopeForUser(Builder $query, int $userId): Builder
    // {
    //     return $query->where('user_id', $userId);
    // }


    public function scopeForDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
