<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Ligne;
use App\Models\Tarif;
use App\Models\Reseau;
use App\Models\Section;
use App\Models\Type;

class SectionControllerTest extends TestCase
{
    public function testIndex()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Section::factory(3)->create();
        $response = $this->get('/api/sections');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "sections" => $response->json('sections')
            ]);
    }
    public function testMessections()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Section::factory(3)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/messections');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "sections" => $response->json('sections')
            ]);
    }

    public function testShow()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        $section = Section::factory()->create();
        $response = $this->get("/api/sections/$section->id");
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "section" => $response->json('section')
            ]);
    }

    public function testStore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/sections', [
            "Depart" => "En ville",
            "Arrivee" => "Banlieu",
            "ligne_id" => 1,
            "tarif_id" => 1
        ]);

        $response->assertStatus(201)
            ->assertJson([
                "message" => $response->json('message'),
                "section" => $response->json('section')
            ]);
    }

    public function testUpdate()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        $section = Section::factory()->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/sections/$section->id", [
            "Depart" => "En ville modifié",
            "Arrivee" => "Banlieu modifié",
            "ligne_id" => 2,
            "tarif_id" => 1
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "section" => $response->json('section')
            ]);
    }

    public function testDestroy()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $section = Section::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($user)->delete("/api/sections/$section->id");

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "section" => $response->json('section')
            ]);
    }

    public function testDelete()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Section::factory(2)->create();
        $section = Section::factory()->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/sections/delete/{$section->id}");

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "section" => $response->json('section')
            ]);
    }

    public function testRestore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Section::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $section = Section::factory()->create(["etat" => "corbeille"]);
        $response = $this->actingAs($user)->patch("/api/sections/restaurer/$section->id");

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "section" => $response->json('section')
            ]);
    }

    public function testDeleted()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Section::factory(2)->create();
        Section::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/sections/deleted/all');

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "sections" => $response->json('sections')
            ]);
    }

    public function testEmptyTrash()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Section::factory(2)->create();
        Section::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/sections/empty-trash');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message')
            ]);
    }
}
