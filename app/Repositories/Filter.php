<?php

namespace App\Repositories;

use Illuminate\Support\Str;

abstract class Filter
{
    public function handle( $request, \Closure $next )
    {
        if(!request()->has($this->filterName())) {
            return $next($request);
        }
        $query = $next($request);

        return $this->applyFilter($query);
    }
    abstract public function applyFilter( $query );

    protected function filterName(  ): string
    {
        return Str::camel(class_basename($this));
    }
}
