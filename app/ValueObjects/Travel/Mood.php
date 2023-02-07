<?php

namespace App\ValueObjects\Travel;

use App\Enums\Travel\Moods;

final class Mood
{
    public function __construct(
        public readonly string $mood,
        public readonly int $value
    ) {
        if (!Moods::isMood($mood)) {
            throw new \InvalidArgumentException('Mood must be one of: ' . implode(', ', Moods::getMoods()) . '.');
        }
    }

    public static function from( string $mood, int $value ): self
    {
        if($value < 0 || $value > 10) {
            throw new \InvalidArgumentException('Value must be between 0 and 10');
        }
        return new self(
            $mood,
            $value * 10
        );
    }

    public function toArray(  ): array
    {
        return [
            $this->mood => $this->value
        ];
    }
}
