<?php

namespace Database\Factories;

use App\Enums\User\Roles;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->randomElement(Roles::getRoles()),
        ];
    }

    public function isAdmin(  ): RoleFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => Roles::Admin->value,
            ];
        });
    }

    public function isUser(  ): RoleFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => Roles::User->value,
            ];
        });
    }

    public function isEditor(  ): RoleFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => Roles::Editor->value,
            ];
        });
    }
}
