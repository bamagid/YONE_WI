<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use App\Models\AdminSystem;
use Illuminate\Support\Facades\Artisan;

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');
    }
    public static function createUser()
    {
        Role::factory()->create();
        Reseau::factory()->create();
    }
    public function testUserRegister()
    {
        $admin = AdminSystem::factory()->create();
        UserControllerTest::createUser();
        $userData = [
            "nom" => "Ba",
            "prenom" => "Abdoul Magid",
            "adresse" => "dakar",
            "telephone" => "778552240",
            "email" => "email@gmail.com",
            "reseau_id" => 1,
            "role_id" => 1
        ];
        $response = $this->actingAs($admin, 'admin')->post('/api/users', $userData);
        $response->assertStatus(201)
            ->assertJson([
                "status" => true,
                "message" => "Bienvenue dans la communauté ",
                "user" => $response->json('user')
            ]);
    }

    public function testUserUpdate()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();

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

        $response = $this->actingAs($user, 'api')->post('/api/users/1', $userData);

        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => $response->json('message'),
                "user" => $response->json('user')
            ]);
    }
    public function testUsersBlocked()
    {
        $admin = AdminSystem::factory()->create();
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $user->update(['etat' => "bloqué"]);
        $response = $this->actingAs($admin, 'admin')->get('/api/users/blocked');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "users" => $response->json('users')
            ]);
    }
    public function testUserIndex()
    {
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get('/api/users');

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "users" => $response->json('users')
            ]);
    }

    public function testLogin()
    {
        UserControllerTest::createUser();
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
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $response = $this->actingAs($user, "api")->get('/api/profile');
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => $response->json('message'),
                "user" => $response->json('user')
            ]);
    }

    public function testRefreshToken()
    {
        UserControllerTest::createUser();
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
        UserControllerTest::createUser();
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
    public function testDestroyUser()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, "admin")->patch('/api/users/' . $user->id, ['motif' => 'motif bidon']);
        $response->assertStatus(200)
            ->assertJson(["message" => $response->json('message')]);
    }

    public function testChangerEtat()
    {
        $admin = AdminSystem::factory()->create();
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $response = $this->actingAs($admin, "admin")->patch('/api/users/' . $user->id, ['motif' => 'motif bidon']);
        $response->assertStatus(200)
            ->assertJson(["message" => $response->json('message')]);
    }
}
