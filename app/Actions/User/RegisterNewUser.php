<?php

namespace App\Actions\User;

use App\DTO\User\User;

class RegisterNewUser
{
    public function handle( User $newUser ): \App\Models\User
    {
        $user = \App\Models\User::make($newUser->toArray());
        $user->save();
        $user->roles()->attach(
            \App\Models\Role::where('name', 'user')->firstOrFail()->id
        );
        return $user;
    }
}
