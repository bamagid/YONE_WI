<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\Type;
use App\Models\User;
use App\Models\Ligne;
use App\Models\Reseau;
use Tests\Feature\UserControllerTest;

class LigneControllerTest extends TestCase
{
    public function __construct()
    {
        $this->migrateFresh();
    }
    public function testLigneIndex()
    {
        Reseau::factory()->create();
        Type::factory(2)->create();
        Ligne::factory(3)->create(["etat" => "corbeille"]);
        $response = $this->get('/api/lignes');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "lignes" => $response->json('lignes')
        ]);
    }
    public function testMesLignes()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();
        Type::factory(2)->create();
        Ligne::factory(3)->create();
        $response = $this->actingAs($user)->get('/api/meslignes');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "lignes" => $response->json('lignes')
        ]);
    }

    public function testLigneShow()
    {
        UserControllerTest::createUser();
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
        UserControllerTest::createUser();
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
        UserControllerTest::createUser();
        Type::factory(2)->create();
        $ligne = Ligne::factory()->create();
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
        UserControllerTest::createUser();
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
        UserControllerTest::createUser();
        Type::factory(2)->create();
        $ligne = Ligne::factory()->create(["etat" => "corbeille"]);
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
        UserControllerTest::createUser();
        Type::factory(2)->create();
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
        UserControllerTest::createUser();
        Type::factory(2)->create();
        Ligne::factory(3)->create(["etat" => "corbeille"]);
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
        UserControllerTest::createUser();
        Type::factory(2)->create();
        Ligne::factory(3)->create(["etat" => "corbeille"]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/lignes/empty-trash');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message')
        ]);
    }
}
