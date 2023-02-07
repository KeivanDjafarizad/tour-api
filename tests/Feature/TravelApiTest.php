<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TravelApiTest extends TestCase
{
    public User $admin;
    public User $editor;

    public function setUp(  ): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $user->save();
        $user->roles()->attach(
            Role::where('name', 'admin')->firstOrFail()->id
        );
        $this->admin = $user;
        $user = User::factory()->create();
        $user->save();
        $user->roles()->attach(
            Role::where('name', 'editor')->firstOrFail()->id
        );
        $this->editor = $user;
    }

    public function tearDown(  ): void
    {
        $this->admin->forceDelete();
        $this->editor->forceDelete();
        parent::tearDown();
    }

     /** @test */
    public function an_admin_can_create_a_travel()
    {
        $travels = [
            [
                'name' => fake()->name,
                'description' => fake()->text,
                'numberOfDays' => fake()->numberBetween(1, 30),
                'moods' => [
                    "nature" => fake()->numberBetween(1, 10) * 10,
                    "relax" => fake()->numberBetween(1, 10) * 10,
                    "history" => fake()->numberBetween(1, 10) * 10,
                    "culture" => fake()->numberBetween(1, 10) * 10,
                    "party" => fake()->numberBetween(1, 10) * 10
                ]
            ],
            [
                'name' => fake()->name,
                'slug' => fake()->slug,
                'description' => fake()->text,
                'numberOfDays' => fake()->numberBetween(1, 30),
                'moods' => [
                    "nature" => fake()->numberBetween(1, 10) * 10,
                    "relax" => fake()->numberBetween(1, 10) * 10,
                    "history" => fake()->numberBetween(1, 10) * 10,
                    "culture" => fake()->numberBetween(1, 10) * 10,
                    "party" => fake()->numberBetween(1, 10) * 10
                ]
            ]
        ];

        Sanctum::actingAs($this->admin);

        foreach($travels as $travel) {
            $response = $this->post(route('travel.store'), $travel);
            $response->assertStatus(201);
            $response->assertJsonStructure([
                'id',
                'name',
                'slug',
                'description',
                'numberOfDays',
                'numberOfNights',
                'moods',
            ]);
        }
    }

    /** @test */
    public function a_non_admin_user_cannot_create_new_travels(  )
    {
        $travel = [
            'name' => fake()->name,
            'description' => fake()->text,
            'numberOfDays' => fake()->numberBetween(1, 30),
            'moods' => [
                "nature" => fake()->numberBetween(1, 10) * 10,
                "relax" => fake()->numberBetween(1, 10) * 10,
                "history" => fake()->numberBetween(1, 10) * 10,
                "culture" => fake()->numberBetween(1, 10) * 10,
                "party" => fake()->numberBetween(1, 10) * 10
            ]
        ];

        Sanctum::actingAs($this->editor);
        $response = $this->post(route('travel.store'), $travel);
        $response->assertStatus(403);
    }

    /** @test */
    public function api_should_throw_if_data_is_incorrect()
    {
        $data = [
            [
                'name' => null,
                'description' => fake()->text,
                'numberOfDays' => fake()->numberBetween(1, 30),
                'moods' => [
                    "nature" => fake()->numberBetween(1, 10) * 10,
                    "relax" => fake()->numberBetween(1, 10) * 10,
                    "history" => fake()->numberBetween(1, 10) * 10,
                    "culture" => fake()->numberBetween(1, 10) * 10,
                    "party" => fake()->numberBetween(1, 10) * 10
                ]
            ],
            [
                'name' => fake()->name,
                'description' => null,
                'numberOfDays' => fake()->numberBetween(1, 30),
                'moods' => [
                    "nature" => fake()->numberBetween(1, 10) * 10,
                    "relax" => fake()->numberBetween(1, 10) * 10,
                    "history" => fake()->numberBetween(1, 10) * 10,
                    "culture" => fake()->numberBetween(1, 10) * 10,
                    "party" => fake()->numberBetween(1, 10) * 10
                ]
            ],
            [
                'name' => fake()->name,
                'description' => fake()->text,
                'numberOfDays' => null,
                'moods' => [
                    "nature" => fake()->numberBetween(1, 10) * 10,
                    "relax" => fake()->numberBetween(1, 10) * 10,
                    "history" => fake()->numberBetween(1, 10) * 10,
                    "culture" => fake()->numberBetween(1, 10) * 10,
                    "party" => fake()->numberBetween(1, 10) * 10
                ]
            ],
            [
                'name' => fake()->name,
                'description' => fake()->text,
                'numberOfDays' => fake()->numberBetween(1, 30),
                'moods' => null
            ],
            [
                'name' => fake()->name,
                'description' => fake()->text,
                'numberOfDays' => fake()->numberBetween(1, 30),
                'moods' => [
                    "random" => fake()->numberBetween(1, 10) * 10,
                    "relax" => fake()->numberBetween(1, 10) * 10,
                    "history" => fake()->numberBetween(1, 10) * 10,
                    "culture" => fake()->numberBetween(1, 10) * 10,
                    "party" => fake()->numberBetween(1, 10) * 10
                ]
            ]
        ];

        Sanctum::actingAs($this->admin);
        foreach($data as $travel) {
            $response = $this->post(route('travel.store'), $travel);
            $response->assertStatus(422);
        }
    }
}
