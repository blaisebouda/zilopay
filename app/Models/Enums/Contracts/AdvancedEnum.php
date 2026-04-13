<?php

namespace App\Models\Enums\Contracts;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait AdvancedEnum
{
    public function equals(AdvancedEnumInterface ...$enums): bool
    {
        return in_array($this, $enums);
    }

    public static function values(): array
    {
        return array_map(fn(AdvancedEnumInterface $enum): string => $enum->value, self::cases());
    }

    public static function labels(): array
    {
        return array_map(fn(AdvancedEnumInterface $enum): string => $enum->label(), self::cases());
    }

    public static function names(): array
    {
        return array_map(fn(AdvancedEnumInterface $enum): string => $enum->name, self::cases());
    }

    public static function tryFromName(string $name): ?AdvancedEnumInterface
    {
        return Arr::first(self::cases(), fn(AdvancedEnumInterface $enum): string => Str::lower($enum->name) == Str::lower($name));
    }

    public static function options(): array
    {
        return array_map(fn(AdvancedEnumInterface $case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }

    public static function random(): self
    {
        return Arr::random(self::cases());
    }
}
