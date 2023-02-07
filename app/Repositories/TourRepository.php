<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class TourRepository
{
    private readonly \App\Models\Tour $tour;
    public function __construct(
    ) {
        $this->tour = new \App\Models\Tour();
    }

    public function getTours( \App\Models\Travel $travel, int $perPage = 5 )
    {
        $query = $this->tour->query()->where('travelId', $travel->id);

        $query = app(Pipeline::class)
            ->send($query)
            ->through([
                \App\Repositories\TourFilters\PriceSort::class,
                \App\Repositories\TourFilters\PriceFrom::class,
                \App\Repositories\TourFilters\PriceTo::class,
                \App\Repositories\TourFilters\DateFrom::class,
                \App\Repositories\TourFilters\DateTo::class,
            ])
            ->thenReturn();

        $query->orderBy('startingDate', 'asc');
        if($perPage <= 0) {
            return $query->get();
        }
        return $query->paginate($perPage);
    }
}
