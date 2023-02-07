<?php

namespace Tests\Feature;

use App\Enums\User\Roles;
use App\Models\Role;
use App\Models\Travel;
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
            Role::where('name', Roles::Admin->value)->firstOrFail()->id
        );
        $this->admin = $user;
        $user = User::factory()->create();
        $user->save();
        $user->roles()->attach(
            Role::where('name', Roles::Editor->value)->firstOrFail()->id
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

    /** @test */
    public function an_editor_should_be_able_to_modify_travel(  )
    {
        $travel = Travel::factory()->create([
            'name' => 'Test',
            'description' => 'Test description',
            'numberOfDays' => 20,
            'moods' => [
                "nature" => 20,
                "relax" => 20,
                "history" => 20,
                "culture" => 20,
                "party" => 20
            ]
        ]);

        $data = [
            'name' => 'Test 2',
            'description' => 'Test description 2',
            'numberOfDays' => 30,
            'moods' => [
                "nature" => 30,
                "relax" => 30,
                "history" => 30,
                "culture" => 30,
                "party" => 30
            ]
        ];

        Sanctum::actingAs($this->editor);
        $response = $this->put(route('travel.update', $travel->uuid), $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'slug',
            'description',
            'numberOfDays',
            'numberOfNights',
            'moods',
        ]);
        $travel->refresh();
        $this->assertEquals($travel->uuid, $response->json('id'));
        $this->assertEquals($travel->name, $data['name']);
        $this->assertEquals($travel->description, $data['description']);
        $this->assertEquals($travel->numberOfDays, $data['numberOfDays']);
        $this->assertEquals($travel->moods, $data['moods']);
    }
}
