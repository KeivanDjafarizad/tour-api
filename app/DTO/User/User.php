<?php

namespace App\DTO\User;

use Illuminate\Support\Facades\Hash;

final class User
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) { }

    public static function fromArray( $array ): self
    {
        return new self(
            $array['name'],
            $array['email'],
            Hash::make($array['password']),
        );
    }

    public function toArray(  )
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
