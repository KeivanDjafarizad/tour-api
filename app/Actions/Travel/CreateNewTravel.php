<?php

namespace App\Actions\Travel;

use App\Models\Travel;

class CreateNewTravel
{
    public function handle( \App\DTO\Travel\Travel $travel ): Travel
    {
        $moods = [];
        return Travel::create([
            'name' => $travel->name,
            'description' => $travel->description,
            'slug' => $travel->slug,
            'isPublic' => $travel->isPublic,
            'numberOfDays' => $travel->numberOfDays->days,
            'moods' => $travel->moodList->moods
        ]);
    }
}
