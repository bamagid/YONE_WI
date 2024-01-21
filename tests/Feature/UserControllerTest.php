<?php

namespace Tests\Feature;

use App\Models\AdminSystem;
use App\Models\Reseau;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testRegister()
    {
        $admin = AdminSystem::factory()->create();
        $this->actingAs($admin, 'admin');
        Role::factory()->create();
        Reseau::factory()->create();
        $userData = [
            "nom" => "magid",
            "prenom" => "abdoul",
            "adresse" => "dakar",
            "telephone" => "77855224",
            "email" => "email@gmail.com",
            "reseau_id" => 1,
            "role_id" => 1,
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $response = $this->post('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJson([
                "status" => true,
                "message" => "Bienvenue dans la communauté ",
                "user" => $response->json('user')
            ]);
    }

    public function testLogin()
    {
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
        Role::factory()->create();
        Reseau::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user, "api");
        $response = $this->get('/api/refresh');
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => $response->json('message'),
                "token" => $response->json("token")
            ]);
    }

    public function testLogout()
    {
        Role::factory()->create();
        Reseau::factory()->create();
        $user = User::factory()->create();
        $login =  $this->post('/api/login', ["email" => $user->email, "password" => "password"]);
        $token = $login->Json('token');
        $response = $this->withHeaders(['Authorization' => "Bearer $token)"])->get('/api/logout');
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => $response->json('message')
            ]);
    }
    public function testdestroy()
    {
        Role::factory()->create();
        Reseau::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user, "api");
        $response = $this->get('/api/users' . $user->id);
        $response->assertStatus(200)
            ->assertJson(["Le user a bien été supprimé"]);
    }
}
