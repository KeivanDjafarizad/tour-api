<?php

namespace App\Actions\Travel;

use App\Models\Travel;

class UpdateTravel
{
    public function handle( \App\DTO\Travel\UpdateTravel $travelDto, Travel $travel ): Travel
    {
        $travel->update([
            'name' => $travelDto->name ?? $travel->name,
            'description' => $travelDto->description ?? $travel->description,
            'slug' => $travelDto->slug ?? $travel->slug,
            'isPublic' => $travelDto->isPublic ?? $travel->isPublic,
            'numberOfDays' => $travelDto->numberOfDays ?? $travel->numberOfDays,
            'moods' => $travelDto->moodList ?? $travel->moods,
        ]);
        return $travel;
    }
}
