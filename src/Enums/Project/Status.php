<?php

namespace App\Enums\Project;

enum Status: int
{
    case development = 1;
    case production = 2;
    case maintenance = 3;
    case archived = 4;

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function searchByValue(string $value): ?self
    {
        foreach (self::cases() as $case) {
            if (strcasecmp($case->name, $value) === 0) {
                return $case;
            }
        }
        return null;
    }
}
