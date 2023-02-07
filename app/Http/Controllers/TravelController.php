<?php

namespace App\Http\Controllers;

use App\Actions\Travel\CreateNewTravel;
use App\DTO\Travel\Travel;
use App\Http\Requests\Travel\CreateTravel;
use App\Http\Resources\TravelResource;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    public function __construct(
        private readonly CreateNewTravel $createNewTravel,
    ) { }

    public function store(CreateTravel $request): \Illuminate\Http\JsonResponse
    {
        try {
            $travelDto = Travel::fromArray($request->validated());
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->getMessage(),
            ], 422);
        }

        $travel = $this->createNewTravel->handle($travelDto);

        return response()->json(new TravelResource($travel), 201);
    }
}
