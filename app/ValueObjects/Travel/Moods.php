<?php

namespace App\ValueObjects\Travel;

class Moods
{
    public function __construct(
        public readonly array $moods
    ) { }

    public static function from( array $moods ): self
    {
        $moodsArray = [];
        foreach ($moods as $mood) {
            if($mood instanceof Mood) {
                $moodsArray[$mood->mood] = $mood->value;
            } else {
                throw new \InvalidArgumentException('Mood must be an instance of Mood');
            }
        }
        return new self(
            $moodsArray
        );
    }
}
