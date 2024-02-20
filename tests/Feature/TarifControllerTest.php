<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Tarif;
use App\Models\Reseau;
use Tests\Feature\UserControllerTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TarifControllerTest extends TestCase
{
    public function __construct(){
        $this->migrateFresh();
    }
    public function testTarifIndex()
    {
        $response = $this->get('/api/tarifs');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarifs" => $response->json('tarifs')
        ]);
    }
    public function testTarifMestarifs()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();
        Tarif::factory(3)->create();
        $response = $this->actingAs($user)->get('/api/mestarifs');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarifs" => $response->json('tarifs')
        ]);
    }

    public function testTarifShow()
    {
        UserControllerTest::createUser();
        $tarif = Tarif::factory()->create();
        $response = $this->get("/api/tarifs/$tarif->id");
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testTarifStore()
    {
        UserControllerTest::createUser();
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

    public function testTarifUpdate()
    {
        UserControllerTest::createUser();
        $tarif = Tarif::factory()->create();
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

    public function testTarifDestroy()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $tarif = Tarif::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($user)->delete("/api/tarifs/$tarif->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testTarifDelete()
    {
        UserControllerTest::createUser();
        $tarif = Tarif::factory()->create(["etat" => "corbeille"]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/tarifs/delete/{$tarif->id}");
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testTarifRestore()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $tarif = Tarif::factory()->create(["etat" => "corbeille"]);
        $response = $this->actingAs($user)->patch("/api/tarifs/restaurer/$tarif->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarif" => $response->json('tarif')
        ]);
    }

    public function testTarifDeleted()
    {
        UserControllerTest::createUser();
        Tarif::factory(3)->create(["etat" => "corbeille"]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/tarifs/deleted/all');

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "tarifs" => $response->json('tarifs')
        ]);
    }

    public function testTarifEmptyTrash()
    {
        UserControllerTest::createUser();
        Tarif::factory(3)->create(["etat" => "corbeille"]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/tarifs/empty-trash');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message')
        ]);
    }
}
