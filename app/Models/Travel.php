<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory, HasUuid;

    public $table = 'travels';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isPublic',
        'slug',
        'name',
        'description',
        'numberOfDays',
        'moods',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'isPublic' => 'boolean',
        'moods' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getNumberOfNightsAttribute(): int
    {
        return $this->numberOfDays - 1;
    }

    public function tours(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tour::class, 'travelId');
    }
}
