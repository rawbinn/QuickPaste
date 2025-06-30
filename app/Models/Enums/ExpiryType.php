<?php

namespace App\Models\Enums;

enum ExpiryType: int
{
    case AFTER_VIEW = 1;
    case FIVE_MIN = 2;
    case TEN_MIN = 3;
    case ONE_HOUR = 4;
    case ONE_DAY = 5;
    case NEVER = 6;

    public function toInterval(): ?\DateInterval
    {
        return match ($this) {
            self::FIVE_MIN => new \DateInterval('PT5M'),
            self::TEN_MIN => new \DateInterval('PT10M'),
            self::ONE_HOUR => new \DateInterval('PT1H'),
            self::ONE_DAY => new \DateInterval('P1D'),
            default => null,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
