<?php

namespace App\Repositories\TourFilters;

class PriceFrom extends \App\Repositories\Filter
{

    public function applyFilter( $query )
    {
        return $query->where('price', '>=', request($this->filterName()));
    }
}
