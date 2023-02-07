<?php

namespace App\Http\Controllers\User;

use App\Actions\User\RegisterNewUser;
use App\DTO\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterUser;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterNewUser $registerNewUser,
    ) { }

    public function login( LoginRequest $request ): JsonResponse
    {
        if(auth()->attempt($request->validated()))
        {
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
        return response()->json([
            'message' => 'Invalid Credentials',
        ], 401);
    }

    public function register( RegisterUser $request ): JsonResponse
    {
        $user = User::fromArray($request->validated());
        $newUser = $this->registerNewUser->handle($user);
        return response()->json(new UserResource($newUser), 201);
    }
}
