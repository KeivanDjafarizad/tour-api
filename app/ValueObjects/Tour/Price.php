<?php

namespace App\ValueObjects\Tour;

class Price
{
    public readonly int $cents;
    public readonly string $formatted;
    public function __construct(
        int $cents
    ) {
        $this->cents = $cents;
        $this->formatted = number_format($cents / 100, 2, ',', '.');
    }

    public static function fromFloat( float $price ): self
    {
        if($price < 0) {
            throw new \InvalidArgumentException('Price cannot be negative');
        }
        return new self(
            (int) round($price * 100)
        );
    }

    public static function fromCents( int $cents ): self
    {
        if($cents < 0) {
            throw new \InvalidArgumentException('Price cannot be negative');
        }
        return new self($cents);
    }
}
