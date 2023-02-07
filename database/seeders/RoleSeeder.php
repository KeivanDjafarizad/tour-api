<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        \App\Models\Role::factory()->isAdmin()->create();
        \App\Models\Role::factory()->isEditor()->create();
        \App\Models\Role::factory()->isUser()->create();
    }
}
