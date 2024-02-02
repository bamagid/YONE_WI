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
    public function testSectionIndex()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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
    public function testMesSections()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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

    public function testSectionShow()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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

    public function testSectionStore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/sections', [
            "depart" => "En ville",
            "arrivee" => "Banlieu",
            "ligne_id" => 1,
            "tarif_id" => 1
        ]);

        $response->assertStatus(201)
            ->assertJson([
                "message" => $response->json('message'),
                "section" => $response->json('section')
            ]);
    }

    public function testSectionUpdate()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
        Type::factory(2)->create();
        Ligne::factory(2)->create();
        Tarif::factory(2)->create();
        $section = Section::factory()->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/sections/$section->id", [
            "depart" => "En ville modifié",
            "arrivee" => "Banlieu modifié",
            "ligne_id" => 2,
            "tarif_id" => 1
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "section" => $response->json('section')
            ]);
    }

    public function testSectionDestroy()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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

    public function testSectionDelete()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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

    public function testSectionRestore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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

    public function testSectionDeleted()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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

    public function testSectionEmptyTrash()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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
