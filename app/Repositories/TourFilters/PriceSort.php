<?php

namespace App\Repositories\TourFilters;

class PriceSort extends \App\Repositories\Filter
{

    public function applyFilter( $query )
    {
        return $query->orderBy('price', request($this->filterName()));
    }
}
