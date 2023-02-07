<?php

namespace Database\Factories;

use App\Models\Tour;
use App\ValueObjects\Tour\Price;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tour::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startingDate = now();
        $endingDate = now()->addDays(
            $this->faker->numberBetween(1, 20)
        );
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->name,
            'startingDate' => $startingDate->format('Y-m-d'),
            'endingDate' => $endingDate->format('Y-m-d'),
            'price' => Price::fromFloat($this->faker->randomFloat(2, 0, 1000))->cents,
        ];
    }
}
