<?php

namespace App\Repositories\TourFilters;

class DateFrom extends \App\Repositories\Filter
{

    public function applyFilter( $query )
    {
        return $query->where('startingDate', '>=', request($this->filterName()));
    }
}
