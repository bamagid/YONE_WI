<?php

namespace Database\Seeders;


use App\Models\Role;
use App\Models\User;
use App\Models\Ligne;
use App\Models\Tarif;
use App\Models\Reseau;
use App\Models\Contact;
use App\Models\Section;
use App\Models\Abonnement;
use App\Models\Newsletter;
use App\Models\AdminSystem;
use App\Models\Type;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        AdminSystem::factory()->create();
        Role::factory()->create();
        Reseau::factory(2)->create();
        User::factory()->create();
        Type::factory(3)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Abonnement::factory(3)->create();
        Section::factory(4)->create();
        Contact::factory(3)->create();
        Newsletter::factory(1)->create();
    }
}
