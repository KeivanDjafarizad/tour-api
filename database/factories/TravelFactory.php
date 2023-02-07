<?php

namespace Database\Factories;

use App\Enums\Travel\Moods;
use App\ValueObjects\Travel\Mood;
use App\ValueObjects\Travel\Moods as TravelMoods;
use App\ValueObjects\Travel\NumberOfDays;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Travel;

class TravelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Travel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'slug' => $this->faker->slug,
            'description' => $this->faker->text,
            'isPublic' => $this->faker->boolean,
            'numberOfDays' => NumberOfDays::from($this->faker->numberBetween(1, 300))->days,
            'moods' => TravelMoods::from([
                Mood::from(Moods::Nature->value, $this->faker->numberBetween(1, 10)),
                Mood::from(Moods::Relax->value, $this->faker->numberBetween(1, 10)),
                Mood::from(Moods::History->value, $this->faker->numberBetween(1, 10)),
                Mood::from(Moods::Culture->value, $this->faker->numberBetween(1, 10)),
                Mood::from(Moods::Party->value, $this->faker->numberBetween(1, 10)),
            ])->moods
        ];
    }
}
