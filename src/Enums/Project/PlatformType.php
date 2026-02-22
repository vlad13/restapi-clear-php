<?php

namespace App\Enums\Project;

enum PlatformType: int
{
    case WordPress = 1;
    case Bitrix = 2;
    case Custom = 3;
    case Other = 4;

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
