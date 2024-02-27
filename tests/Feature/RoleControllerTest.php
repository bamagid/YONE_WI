<?php

use Tests\TestCase;
use App\Models\role;
use App\Models\AdminSystem;
use Illuminate\Support\Facades\Artisan;

class RoleControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');
    }
    public function testRoleIndex()
    {
        Role::factory(3)->create();
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get('/api/roles');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "roles" => $response->json('roles')
            ]);
    }

    public function testRoleStore()
    {
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->post('/api/roles', [
            "nom" => "admineseau",
        ]);

        $response->assertStatus(201)
            ->assertJson([
                "message" => $response->json('message'),
                "role" => $response->json('role')
            ]);
    }

    public function testRoleUpdate()
    {
        $role = Role::factory()->create();
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->patch("/api/roles/$role->id", [
            "nom" => "admin",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "role" => $response->json('role')
            ]);
    }

    public function testRoleDestroy()
    {
        $admin = AdminSystem::factory()->create();
        $role = Role::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($admin, 'admin')->delete("/api/roles/$role->id");

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "role" => $response->json('role')
            ]);
    }

    public function testRoleDelete()
    {
        $role = Role::factory()->create(["etat" => "corbeille"]);
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->put("/api/roles/delete/$role->id");
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "role" => $response->json('role')
            ]);
    }

    public function testRoleRestore()
    {
        $admin = AdminSystem::factory()->create();
        $role = Role::factory()->create(["etat" => "corbeille"]);
        $response = $this->actingAs($admin, 'admin')->put("/api/roles/restaurer/$role->id");

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "role" => $response->json('role')
            ]);
    }

    public function testRoleDeleted()
    {
        Role::factory(3)->create(["etat" => "corbeille"]);
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get('/api/roles/deleted');

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "roles" => $response->json('roles')
            ]);
    }

    public function testRoleEmptyTrash()
    {
        Role::factory(3)->create(["etat" => "corbeille"]);
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->post('/api/roles/empty-trash');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message')
            ]);
    }
}
