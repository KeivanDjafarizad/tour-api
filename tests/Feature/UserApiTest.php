<?php

namespace Tests\Feature;

use App\Enums\User\Roles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    public User $user;
    public User $admin;
    public function setUp(  ): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $user->save();
        $user->roles()->attach(
            Role::where('name', Roles::User->value)->firstOrFail()->id
        );
        $this->user = $user;
        $user = User::factory()->create();
        $user->save();
        $user->roles()->attach(
            Role::where('name', Roles::Admin->value)->firstOrFail()->id
        );
        $this->admin = $user;
    }

    public function tearDown(  ): void
    {
        $this->user->forceDelete();
        $this->admin->forceDelete();
        parent::tearDown();
    }

    /** @test */
    public function loging_as_registered_user()
    {
        $response = $this->post(route('auth.login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
    }

    /** @test */
    public function login_with_wrong_data(  )
    {
        $data = [
            [
                'email' => fake()->email,
                'password' => 'password',
            ],
            [
                'email' => $this->user->email,
                'password' => 'wrong_password',
            ]
        ];

        foreach ($data as $item) {
            $response = $this->post(route('auth.login'), $item);
            $response->assertStatus(401);
            $response->assertJsonStructure([
                'message',
            ]);
        }
    }

    /** @test */
    public function loging_with_missing_data()
    {
        $data = [
            [
                'email' => null,
                'password' => null,
            ],
            [
                'email' => null,
                'password' => 'password'
            ],
            [
                'email' => $this->user->email,
                'password' => null
            ],
        ];

        foreach ($data as $item) {
            $response = $this->post(route('auth.login'), $item);
            $response->assertStatus(401);
            $response->assertJsonStructure([
                'message',
            ]);
        }
    }

    /** @test */
    public function an_admin_can_register_new_user(  )
    {
        $newUserData = [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => 'password',
        ];

        Sanctum::actingAs($this->admin);
        $response = $this->post(route('auth.register'), $newUserData);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $newUserData['name'],
            'email' => $newUserData['email'],
        ]);
    }

    /** @test */
    public function a_user_cannot_register_new_user(  )
    {
        $newUserData = [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => 'password',
        ];

        Sanctum::actingAs($this->user);
        $response = $this->post(route('auth.register'), $newUserData);
        $response->assertStatus(403);
    }

    /** @test */
    public function a_user_should_not_be_registered_if_incorrect_data(  )
    {
        $userDataArray = [
            [
                'name' => null,
                'email' => null,
                'password' => null,
            ],
            [
                'name' => null,
                'email' => fake()->email,
                'password' => 'password',
            ],
            [
                'name' => fake()->name,
                'email' => null,
                'password' => 'password',
            ],
            [
                'name' => fake()->name,
                'email' => fake()->email,
                'password' => null,
            ],
        ];

        Sanctum::actingAs($this->admin);
        foreach ($userDataArray as $userData) {
            $response = $this->post(route('auth.register'), $userData);
            $response->assertStatus(422);
        }

    }
}
