<?php

namespace App\Actions\Tour;

use App\DTO\Tour\Tour;
use App\Models\Travel;

class CreateNewTour
{
    public function handle( Tour $tour, int $travelId ): \App\Models\Tour
    {
        $newTour = new \App\Models\Tour();
        $newTour->name = $tour->name;
        $newTour->startingDate = $tour->startingDate;
        $newTour->endingDate = $tour->endingDate;
        $newTour->price = $tour->price;
        $newTour->travelId = $travelId;
        $newTour->save();

        return $newTour;
    }
}
