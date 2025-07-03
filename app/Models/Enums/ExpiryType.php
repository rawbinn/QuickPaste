<?php

declare(strict_types=1);

namespace App\Models\Enums;

use DateInterval;

enum ExpiryType: int
{
    case AFTER_VIEW = 1;
    case FIVE_MIN = 2;
    case TEN_MIN = 3;
    case ONE_HOUR = 4;
    case ONE_DAY = 5;
    case NEVER = 6;

    public function toInterval(): ?DateInterval
    {
        return match ($this) {
            self::FIVE_MIN => new DateInterval('PT5M'),
            self::TEN_MIN => new DateInterval('PT10M'),
            self::ONE_HOUR => new DateInterval('PT1H'),
            self::ONE_DAY => new DateInterval('P1D'),
            default => null,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return [
            self::NEVER->value => 'Never expire',
            self::AFTER_VIEW->value => 'After one view',
            self::FIVE_MIN->value => '5 minutes',
            self::TEN_MIN->value => '10 minutes',
            self::ONE_HOUR->value => '1 hour',
            self::ONE_DAY->value => '1 day',
        ];
    }
}
