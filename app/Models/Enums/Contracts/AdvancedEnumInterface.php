<?php

namespace App\Models\Enums\Contracts;

interface AdvancedEnumInterface
{
    public function label(): string;

    public function equals(AdvancedEnumInterface ...$enums): bool;

    public static function values(): array;

    public static function labels(): array;

    public static function options(): array;
}
