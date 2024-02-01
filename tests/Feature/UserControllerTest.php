<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use App\Models\AdminSystem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    public function testRegister()
    {
        $this->artisan('migrate:fresh');
        $admin = AdminSystem::factory()->create();
        $this->actingAs($admin, 'admin');
        Role::factory()->create();
        Reseau::factory()->create();
        $userData = [
            "nom" => "magid",
            "prenom" => "abdoul",
            "adresse" => "dakar",
            "telephone" => "778552240",
            "email" => "email@gmail.com",
            "reseau_id" => 1,
            "role_id" => 1,
            "password" => "Password1@",
            "password_confirmation" => "Password1@"
        ];

        $response = $this->post('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJson([
                "status" => true,
                "message" => "Bienvenue dans la communauté ",
                "user" => $response->json('user')
            ]);
    }

    public function testUpdate()
    {
        $this->artisan('migrate:fresh');
        Role::factory()->create();
        Reseau::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $userData = [
            "nom" => "magid",
            "prenom" => "abdoul",
            "adresse" => "dakar",
            "telephone" => "778552240",
            "email" => "email@gmail.com",
            "reseau_id" => 1,
            "role_id" => 1,
            "password" => "Password1@",
            "password_confirmation" => "Password1@"
        ];

        $response = $this->post('/api/users/1', $userData);

        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => $response->json('message'),
                "user" => $response->json('user')
            ]);
    }
    public function testusersblocked()
    {
        $this->artisan('migrate:fresh');
        $admin = AdminSystem::factory()->create();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/api/users/blocked');

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "users" => $response->json('users')
            ]);
    }
    public function testindex()
    {
        $this->artisan('migrate:fresh');
        $admin = AdminSystem::factory()->create();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/api/users');

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "users" => $response->json('users')
            ]);
    }

    public function testLogin()
    {
        $this->artisan('migrate:fresh');
        Role::factory()->create();
        Reseau::factory()->create();
        $user = AdminSystem::factory()->create();
        $response =  $this->post('/api/login', ["email" => $user->email, "password" => "password"]);
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "type" => $response->json('type'),
                "message" => $response->json('message'),
                "user" => $response->json('user'),
                "token" => $response->json('token')
            ]);
    }

    public function testProfile()
    {
        $this->artisan('migrate:fresh');
        Role::factory()->create();
        Reseau::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user, "api");
        $response = $this->get('/api/profile');
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => $response->json('message'),
                "user" => $response->json('user')
            ]);
    }

    public function testRefreshToken()
    {
        $this->artisan('migrate:fresh');
        Role::factory()->create();
        Reseau::factory()->create();
        $user = User::factory()->create();
        $login =  $this->post('/api/login', ["email" => $user->email, "password" => "Password1@"]);
        $token = $login->Json('token');
        $response = $this->withHeaders(['Authorization' => "Bearer $token)"])->get('/api/refresh');
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => $response->json('message'),
                "token" => $response->json("token")
            ]);
    }

    public function testLogout()
    {
        $this->artisan('migrate:fresh');
        Role::factory()->create();
        Reseau::factory()->create();

        $user = User::factory()->create();
        $login =  $this->post('/api/login', ["email" => $user->email, "password" => "Password1@"]);
        $token = $login->Json('token');
        $response = $this->withHeaders(['Authorization' => "Bearer $token)"])->get('/api/logout');
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => $response->json('message')
            ]);
    }
    public function testdestroyuser()
    {
        $this->artisan('migrate:fresh');
        Role::factory()->create();
        Reseau::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user, "api");
        $response = $this->patch('/api/users/' . $user->id, ['motif' => 'motif bidon']);
        $response->assertStatus(200)
            ->assertJson(["message" => $response->json('message')]);
    }

    public function testchangerEtat()
    {
        $this->artisan('migrate:fresh');
        $admin = AdminSystem::factory()->create();
        $this->actingAs($admin, "admin");
        Role::factory()->create();
        Reseau::factory()->create();
        $user = User::factory()->create();
        $response = $this->patch('/api/users/' . $user->id, ['motif' => 'motif bidon']);
        $response->assertStatus(200)
            ->assertJson(["message" => $response->json('message')]);
    }
}
