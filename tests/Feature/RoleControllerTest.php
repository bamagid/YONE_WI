<?php

use Tests\TestCase;
use App\Models\AdminSystem;
use App\Models\role;

class RoleControllerTest extends TestCase
{
    public function testRoleIndex()
    {
        $this->artisan('migrate:fresh');
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
        $this->artisan('migrate:fresh');
        Role::factory()->create();
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->post('/api/roles', [
            "nom" => "admin reseau",
        ]);

        $response->assertStatus(201)
            ->assertJson([
                "message" => $response->json('message'),
                "role" => $response->json('role')
            ]);
    }

    public function testRoleUpdate()
    {
        $this->artisan('migrate:fresh');
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
        $this->artisan('migrate:fresh');
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
        $this->artisan('migrate:fresh');
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
        $this->artisan('migrate:fresh');
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
        $this->artisan('migrate:fresh');
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
        $this->artisan('migrate:fresh');
        Role::factory(3)->create(["etat" => "corbeille"]);
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->post('/api/roles/empty-trash');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message')
            ]);
    }
}