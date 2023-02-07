<?php

namespace App\DTO\Travel;

use App\ValueObjects\Travel\Mood;
use App\ValueObjects\Travel\Moods;
use App\ValueObjects\Travel\NumberOfDays;
use Illuminate\Support\Str;

class Travel
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $slug,
        public readonly bool $isPublic,
        public readonly NumberOfDays $numberOfDays,
        public readonly Moods $moodList
    ) { }

    public static function fromArray( array $data ): self
    {
        $moods = [];
        foreach ($data['moods'] as $mood => $value) {
            $moodToInsert = new Mood($mood, $value);
            $moods[] = $moodToInsert;
        }

        return new self(
            $data['name'],
            $data['description'],
            $data['slug'] ?? Str::slug($data['name']),
            $data['isPublic'] ?? false,
            NumberOfDays::from($data['numberOfDays']),
            Moods::from($moods)
        );
    }
}
