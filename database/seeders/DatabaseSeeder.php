<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\AdminSystem::factory()->create();
        \App\Models\Role::factory()->create();
        \App\Models\Reseau::factory()->create();
        \App\Models\User::factory()->create();
    }
}
