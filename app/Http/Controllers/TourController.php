<?php

namespace App\Http\Controllers;

use App\Actions\Tour\CreateNewTour;
use App\Http\Requests\Tour\CreateTour;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use App\Repositories\TourRepository;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function __construct(
        private readonly CreateNewTour $createNewTour,
    ) { }

    public function tours( string $slug, Request $request )
    {
        $travel = Travel::where('slug', $slug)->firstOrFail();
        return TourResource::collection(
            (new TourRepository())->getTours($travel, 5)
        );
    }

    public function store( Travel $travel, CreateTour $request )
    {
        $tour = $this->createNewTour->handle(
            \App\DTO\Tour\Tour::fromArray( $request->validated() ),
            $travel->id
        );
        return response()->json( new TourResource($tour), 201 );
    }
}
