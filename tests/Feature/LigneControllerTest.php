<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Ligne;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LigneControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/api/lignes');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => "La liste des lignes actives"
        ]);
    }

    public function testShow()
    {
        $ligne = Ligne::factory()->create();
        $response = $this->get("/api/lignes/{$ligne->id}");
        $response->assertStatus(200);
        $response->assertJson([
            "message" => "Voici la ligne que vous recherchez"
        ]);
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/lignes', [
            "nom" => "Nouvelle Ligne",
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            "message" => "La ligne a bien été enregistrée"
        ]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $ligne = Ligne::factory()->create();

        $response = $this->actingAs($user)->patch("/api/lignes/{$ligne->id}", [
            "nom" => "Ligne Modifiée",
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            "message" => "La ligne a bien été mise à jour"
        ]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $ligne = Ligne::factory()->create(["etat" => "actif"]);

        $response = $this->actingAs($user)->delete("/api/lignes/{$ligne->id}");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => "La ligne a bien été mise dans la corbeillee"
        ]);
    }

    public function testDelete()
    {
        $user = User::factory()->create();
        $ligne = Ligne::factory()->create(["etat" => "corbeille"]);

        $response = $this->actingAs($user)->patch("/api/lignes/delete/{$ligne->id}");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => "La ligne a bien été supprimée"
        ]);
    }

    public function testRestore()
    {
        $user = User::factory()->create();
        $ligne = Ligne::factory()->create(["etat" => "corbeille"]);

        $response = $this->actingAs($user)->patch("/api/lignes/restaurer/{$ligne->id}");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => "La ligne a bien été restaurée"
        ]);
    }

    public function testDeleted()
    {
        Ligne::factory(3)->create(["etat" => "corbeille"]);

        $response = $this->get('/api/lignes/deleted/all');

        $response->assertStatus(200);
        $response->assertJson([
            "message" => "La liste des lignes qui se trouve dans la corbeille"
        ]);
    }

    public function testEmptyTrash()
    {
        Ligne::factory(3)->create(["etat" => "corbeille"]);

        $response = $this->post('/api/lignes/empty-trash');

        $response->assertStatus(200);
        $response->assertJson([
            "message" => "La corbeille a été vidée avec succès"
        ]);
    }
}
