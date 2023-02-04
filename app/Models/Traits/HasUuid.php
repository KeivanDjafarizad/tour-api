<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Nonstandard\Uuid;

trait HasUuid
{
    public static function bootHasUuid(  ): void
    {
        static::creating(function (Model $model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }
}
