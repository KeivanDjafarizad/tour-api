<?php

namespace App\DTO\Travel;

use App\ValueObjects\Travel\Mood;
use App\ValueObjects\Travel\Moods;
use App\ValueObjects\Travel\NumberOfDays;

class UpdateTravel
{

    public function __construct(
        public readonly string|null $name,
        public readonly string|null $description,
        public readonly string|null $slug,
        public readonly bool|null $isPublic,
        public readonly int|null $numberOfDays,
        public readonly array|null $moodList
    ) { }

    public static function fromArray( array $data ): self
    {
        $moods = [];
        if($data['moods']) {
            foreach ($data['moods'] as $mood => $value) {
                $moodToInsert = new Mood($mood, $value);
                $moods[] = $moodToInsert;
            }
        }

        return new self(
            $data['name'] ?? null,
            $data['description'] ?? null,
            $data['slug'] ?? null,
            $data['isPublic'] ?? null,
            $data['numberOfDays'] ? NumberOfDays::from($data['numberOfDays'])->days : null,
            $moods !== [] ? Moods::from($moods)->moods : null
        );
    }
}
