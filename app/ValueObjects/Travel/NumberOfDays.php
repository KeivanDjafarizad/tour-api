<?php

namespace App\ValueObjects\Travel;

final class NumberOfDays
{
    public function __construct(
        public readonly int $days
    ) {
        if ($days < 1) {
            throw new \InvalidArgumentException('Number of days must be greater than 0');
        }
    }

    public static function from( int|float|string $days ): self
    {
        return new self((int) $days);
    }
}
