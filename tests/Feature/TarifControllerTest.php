<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use App\Models\Tarif;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TarifControllerTest extends TestCase
{
    public function testIndex()
    {
        $this->artisan('migrate:fresh');
        $response = $this->get('/api/tarifs');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarifs" => $response->json('tarifs')
        ]);
    }
    public function testMestarifs()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        Tarif::factory(3)->create();
        $response = $this->actingAs($user)->get('/api/mestarifs');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarifs" => $response->json('tarifs')
        ]);
    }

    public function testShow()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        $tarif = Tarif::factory()->create();
        $response = $this->get("/api/tarifs/$tarif->id");
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testStore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/tarifs', [
            "prix" => 60,
            "type" => "entre section",
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testUpdate()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        $tarif = Tarif::factory()->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/tarifs/$tarif->id", [
            "prix" => 500,
            "type" => "trajet",
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testDestroy()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $tarif = Tarif::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($user)->delete("/api/tarifs/$tarif->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testDelete()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        $tarif = Tarif::factory()->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/tarifs/delete/{$tarif->id}");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testRestore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $tarif = Tarif::factory()->create(["etat" => "corbeille"]);
        $response = $this->actingAs($user)->patch("/api/tarifs/restaurer/$tarif->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testDeleted()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Tarif::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/tarifs/deleted/all');

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarifs" => $response->json('tarifs')
        ]);
    }

    public function testEmptyTrash()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Tarif::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/tarifs/empty-trash');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message')
        ]);
    }
}
