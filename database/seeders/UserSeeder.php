<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create()->each(function ($user) {
            $role = Role::where('name', 'user')->firstOrFail();
            $user->roles()->attach($role->id);
        });

        User::factory()->count(1)->create()->each(function ($user) {
            $role = Role::where('name', 'admin')->firstOrFail();
            $user->roles()->attach($role->id);
        });
    }
}
