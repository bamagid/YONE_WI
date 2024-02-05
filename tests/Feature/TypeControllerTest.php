<?php
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use App\Models\Type;
use Tests\Feature\UserControllerTest;

class TypeControllerTest extends TestCase
{
    public function testTypeIndex()
    {
        $this->artisan('migrate:fresh');
        $response = $this->get('/api/types');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "types" => $response->json('types')
        ]);
    }
    public function testTypeMestypes()
    {
        $this->artisan('migrate:fresh');
        UserControllerTest::createUser();
        $user = User::factory()->create();
        Type::factory(3)->create();
        $response = $this->actingAs($user)->get('/api/mestypes');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "types" => $response->json('types')
        ]);
    }

    public function testTypeShow()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
        $type = Type::factory()->create();
        $response = $this->get("/api/types/$type->id");
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "type" => $response->json('type')
        ]);
    }

    public function testTypeStore()
    {
        $this->artisan('migrate:fresh');
        UserControllerTest::createUser();
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

    public function testTypeUpdate()
    {
        $this->artisan('migrate:fresh');
        UserControllerTest::createUser();
        $type = Type::factory()->create();
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

    public function testTypeDestroy()
    {
        $this->artisan('migrate:fresh');
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $type = Type::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($user)->delete("/api/types/$type->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "type" => $response->json('type')
        ]);
    }

    public function testTypeDelete()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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

    public function testTypeRestore()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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

    public function testTypeDeleted()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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

    public function testTypeEmptyTrash()
    {
        $this->artisan('migrate:fresh');
        Reseau::factory()->create();
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
