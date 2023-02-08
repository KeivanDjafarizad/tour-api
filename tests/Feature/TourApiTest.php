<?php

namespace Tests\Feature;

use App\Enums\User\Roles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TourApiTest extends TestCase
{
    public User $admin;
    public User $editor;
    public User $user;

    public function setUp(  ): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $user->roles()->attach(
            Role::where('name', Roles::Admin->value)->firstOrFail()->id
        );
        $user->save();
        $this->admin = $user;
        $user = User::factory()->create();
        $user->roles()->attach(
            Role::where('name', Roles::Editor->value)->firstOrFail()->id
        );
        $user->save();
        $this->editor = $user;
        $user = User::factory()->create();
        $user->roles()->attach(
            Role::where('name', Roles::User->value)->firstOrFail()->id
        );
        $user->save();
        $this->user = $user;
    }

    public function tearDown(  ): void
    {
        $this->admin->forceDelete();
        $this->editor->forceDelete();
        $this->user->forceDelete();
        parent::tearDown();
    }

    /** @test */
    public function an_admin_can_create_new_tour_for_travel()
    {
        $startingDate = fake()->date;
        $endingDate = fake()->dateTimeBetween($startingDate, '+1 year')->format('Y-m-d');
        $tourInfo = [
            'name' => fake()->name,
            'startingDate' => $startingDate,
            'endingDate' => $endingDate,
            'price' => fake()->numberBetween(100, 10000),
        ];

        $travel = \App\Models\Travel::factory()->create();

        Sanctum::actingAs($this->admin);
        $response = $this->postJson(route('travel.tour.store', $travel->uuid), $tourInfo);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'startingDate',
                'endingDate',
                'price',
            ]
        ]);
        $response->assertJson([
            'data' => [
                'name' => $tourInfo['name'],
                'startingDate' => $tourInfo['startingDate'],
                'endingDate' => $tourInfo['endingDate'],
                'price' => $tourInfo['price'],
            ]
        ]);
        self::assertCount(1, $travel->tours);
    }

    /** @test */
    public function an_admin_cannot_create_new_tour_for_nonexistent_travel(  )
    {
        $startingDate = fake()->date;
        $endingDate = fake()->dateTimeBetween($startingDate, '+1 year')->format('Y-m-d');
        $tourInfo = [
            'name' => fake()->name,
            'startingDate' => $startingDate,
            'endingDate' => $endingDate,
            'price' => fake()->numberBetween(100, 10000),
        ];
        $travelId = fake()->uuid;
        Sanctum::actingAs($this->admin);
        $response = $this->postJson(route('travel.tour.store', $travelId), $tourInfo);
        $response->assertStatus(404);
    }

    /** @test */
    public function an_non_admin_user_cannot_create_new_tour(  )
    {
        $startingDate = fake()->date;
        $endingDate = fake()->dateTimeBetween($startingDate, '+1 year')->format('Y-m-d');
        $tourInfo = [
            'name' => fake()->name,
            'startingDate' => $startingDate,
            'endingDate' => $endingDate,
            'price' => fake()->numberBetween(100, 10000),
        ];
        $travel = \App\Models\Travel::all()->random();
        Sanctum::actingAs($this->user);
        $response = $this->postJson(route('travel.tour.store', $travel->uuid), $tourInfo);
        $response->assertStatus(403);
        Sanctum::actingAs($this->editor);
        $response = $this->postJson(route('travel.tour.store', $travel->uuid), $tourInfo);
        $response->assertStatus(403);
    }

    /** @test */
    public function an_admin_cannot_create_tours_with_wrong_data(  )
    {
        $startingDate = fake()->date;
        $data = [
            [
                'name' => null,
                'startingDate' => $startingDate,
                'endingDate' => fake()->dateTimeBetween($startingDate, '+1 month')->format('Y-m-d'),
                'price' => fake()->numberBetween(100, 10000),
            ],
            [
                'name' => fake()->name,
                'startingDate' => null,
                'endingDate' => fake()->dateTimeBetween($startingDate, '+1 month')->format('Y-m-d'),
                'price' => fake()->numberBetween(100, 10000),
            ],
            [
                'name' => fake()->name,
                'startingDate' => $startingDate,
                'endingDate' => null,
                'price' => fake()->numberBetween(100, 10000),
            ],
            [
                'name' => fake()->name,
                'startingDate' => $startingDate,
                'endingDate' => date('Y-m-d', strtotime($startingDate . ' -1 day')),
                'price' => fake()->numberBetween(100, 10000),
            ]
        ];

        $travel = \App\Models\Travel::all()->random();
        foreach ($data as $tourInfo) {
            Sanctum::actingAs($this->admin);
            $response = $this->postJson(route('travel.tour.store', $travel->uuid), $tourInfo);
            $response->assertStatus(422);
        }
    }
}
