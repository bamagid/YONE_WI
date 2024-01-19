<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ApiControllerTest extends TestCase
{
    use RefreshDatabase;
    const credentials = [
        "email" => "john@example.com",
        "password" => "password"
    ];
    public function testRegister()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $response = $this->post('/api/register', $userData);

        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => "User registered successfully"
            ]);
    }

    public function testLogin()
    {
        User::factory()->create();
        $response =  $this->post('/api/login', self::credentials);
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => "User logged in succcessfully",
                "token" => $response->json('token')
            ]);
    }

    public function testProfile()
    {
        $user = User::factory()->create();
        $this->actingAs($user, "api");
        $response = $this->get('/api/profile');

        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => "Profile data",
                "data" => $response->json('data')
            ]);
    }

    public function testRefreshToken()
    {
        $user = User::factory()->create();
        $this->actingAs($user, "api");
        $response = $this->get('/api/refresh');
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => "New access token",
                "token" => $response->json("token")
            ]);
    }

    public function testLogout()
    {
        User::factory()->create();
        $login = $this->post('/api/login', self::credentials);
        $token = $login->Json('token');
        $response = $this->withHeaders(['Authorization' => "Bearer $token)"])->get('/api/logout');
        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => "User logged out successfully"
            ]);
    }
}
