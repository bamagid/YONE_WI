<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TypeControllerTest extends TestCase
{
    public function testIndex()
    {
        $this->artisan('migrate:fresh');
        $response = $this->get('/api/types');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "types" => $response->json('types')
        ]);
    }
    public function testMestypes()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        Type::factory(3)->create();
        $response = $this->actingAs($user)->get('/api/mestypes');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "types" => $response->json('types')
        ]);
    }

    public function testShow()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        $type = Type::factory()->create();
        $response = $this->get("/api/types/$type->id");
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "type" => $response->json('type')
        ]);
    }

    public function testStore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/types', [
            "nom" => "urbain",
            "description" => "Ces lignes ne concernent que la  zone a la quelle elles sont assignées",
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            "message" => $response->json('message'),
            "type" => $response->json('type')
        ]);
    }

    public function testUpdate()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        $type = Type::factory()->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/types/$type->id", [
            "nom" => "Ligne Banlieu ",
            "description" => "cette ligne là ne concerne que la  zone a la quelle elles sont assignées ",
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "type" => $response->json('type')
        ]);
    }

    public function testDestroy()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $type = Type::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($user)->delete("/api/types/$type->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "type" => $response->json('type')
        ]);
    }

    public function testDelete()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        $type = Type::factory()->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/types/delete/{$type->id}");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "type" => $response->json('type')
        ]);
    }

    public function testRestore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Role::factory()->create();
        $user = User::factory()->create();
        $type = Type::factory()->create(["etat" => "corbeille"]);
        $response = $this->actingAs($user)->patch("/api/types/restaurer/$type->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "type" => $response->json('type')
        ]);
    }

    public function testDeleted()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/types/deleted/all');

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "types" => $response->json('types')
        ]);
    }

    public function testEmptyTrash()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory(2)->create();
        Type::factory(3)->create(["etat" => "corbeille"]);
        Role::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/types/empty-trash');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message')
        ]);
    }
}
