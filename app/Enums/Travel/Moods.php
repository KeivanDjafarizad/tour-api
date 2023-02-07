<?php

namespace App\Enums\Travel;

enum Moods: string
{
    case Nature = 'nature';
    case Relax = 'relax';
    case History = 'history';
    case Culture = 'culture';
    case Party = 'party';

    public static function isMood( string $mood ): bool
    {
        return match (true) {
            $mood === self::Nature->value, $mood === self::Relax->value, $mood === self::History->value, $mood === self::Culture->value, $mood === self::Party->value => true,
            default => false,
        };
    }

    public static function getMoods(  ): array
    {
        return [
            self::Nature->value,
            self::Relax->value,
            self::History->value,
            self::Culture->value,
            self::Party->value,
        ];
    }
}
