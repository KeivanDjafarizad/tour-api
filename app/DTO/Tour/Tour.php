<?php

namespace App\DTO\Tour;

use App\ValueObjects\Tour\Price;

class Tour
{
    public function __construct(
        public string $name,
        public string $startingDate,
        public string $endingDate,
        public int $price,
    ) { }

    public static function fromArray( array $data ): self
    {
        return new self(
            $data['name'],
            $data['startingDate'],
            $data['endingDate'],
            Price::fromCents($data['price'])->cents,
        );
    }
}
