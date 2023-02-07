<?php

namespace App\Http\Controllers;

use App\Actions\Travel\CreateNewTravel;
use App\Actions\Travel\UpdateTravel;
use App\DTO\Travel\Travel as TravelDTO;
use App\DTO\Travel\UpdateTravel as UpdateTravelDTO;
use App\Http\Requests\Travel\CreateTravel as CreateTravelRequest;
use App\Http\Requests\Travel\UpdateTravel as UpdateTravelRequest;
use App\Http\Resources\TravelCollection;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

class TravelController extends Controller
{
    public function __construct(
        private readonly CreateNewTravel $createNewTravel,
        private readonly UpdateTravel $updateTravel
    ) { }

    public function index(  ): TravelCollection
    {
        $travels = Travel::public()->paginate(5);
        return new TravelCollection( $travels );
    }

    public function store(CreateTravelRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $travelDto = TravelDTO::fromArray($request->validated());
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->getMessage(),
            ], 422);
        }

        $travel = $this->createNewTravel->handle($travelDto);

        return response()->json(new TravelResource($travel), 201);
    }

    public function update( Travel $travel, UpdateTravelRequest $request ): \Illuminate\Http\JsonResponse
    {
        try {
            $travelDto = UpdateTravelDTO::fromArray($request->validated());
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->getMessage(),
            ], 422);
        }

        $travel = $this->updateTravel->handle($travelDto, $travel);

        return response()->json(new TravelResource($travel), 200);
    }
}
