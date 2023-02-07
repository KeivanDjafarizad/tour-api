<?php

namespace App\Enums\User;

enum Roles: string
{
    case Admin = 'admin';
    case User = 'user';
    case Editor = 'editor';

    public static function getRoles(): array
    {
        return [
            self::Admin,
            self::User,
            self::Editor,
        ];
    }
}
