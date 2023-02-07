<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $travels = \App\Models\Travel::all();
        Tour::factory()->count(10)->make()->each(function ($tour) use ($travels) {
            $travel = $travels->random();
            $tour->travelId = $travel->id;
            $tour->save();
        });
    }
}
