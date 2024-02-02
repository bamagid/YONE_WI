<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Ligne;
use App\Models\Reseau;
use App\Models\Type;

class LigneControllerTest extends TestCase
{
    public function testLigneIndex()
    {
        $this->artisan('migrate:fresh');
        $response = $this->get('/api/lignes');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "lignes" => $response->json('lignes')
        ]);
    }
    public function testMesLignes()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/meslignes');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "lignes" => $response->json('lignes')
        ]);
    }

    public function testLigneShow()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        $ligne = Ligne::factory()->create();
        $response = $this->get("/api/lignes/$ligne->id");
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "ligne" => $response->json('ligne')
        ]);
    }

    public function testLigneStore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        Type::factory(2)->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/lignes', [
            "nom" => "Nouvelle Ligne",
            "type_id" => 1,
            'lieuDepart' => "dakar",
            'lieuArrivee' => "mermoz"
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            "message" => $response->json('message'),
            "ligne" => $response->json('ligne')
        ]);
    }

    public function testLigneUpdate()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        $ligne = Ligne::factory()->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/lignes/$ligne->id", [
            "nom" => "Ligne Modifiée",
            "type_id" => 1,
            'lieuDepart' => "dakar",
            'lieuArrivee' => "mermoz"
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "ligne" => $response->json('ligne')
        ]);
    }

    public function testLigneDestroy()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        Type::factory(2)->create();
        $ligne = Ligne::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($user)->delete("/api/lignes/$ligne->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "ligne" => $response->json('ligne')
        ]);
    }

    public function testLigneDelete()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        $ligne = Ligne::factory()->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/lignes/delete/{$ligne->id}");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "ligne" => $response->json('ligne')
        ]);
    }

    public function testLigneRestore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $ligne = Ligne::factory()->create(["etat" => "corbeille"]);
        $response = $this->actingAs($user)->patch("/api/lignes/restaurer/$ligne->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "ligne" => $response->json('ligne')
        ]);
    }

    public function testLigneDeleted()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/lignes/deleted/all');

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "lignes" => $response->json('lignes')
        ]);
    }

    public function testLigneEmptyTrash()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(2)->create();
        Ligne::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/lignes/empty-trash');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message')
        ]);
    }
}
