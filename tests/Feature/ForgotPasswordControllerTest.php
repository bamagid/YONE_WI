<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\UserControllerTest;

class ForgotPasswordControllerTest extends TestCase
{
    public function testSubmitForgetPasswordForm()
    {
         $this->artisan('migrate:fresh');
        UserControllerTest::createUser();
        User::factory()->create();
        $response = $this->post('/api/forget-password', [
            'email' => "dioufgermainedaba@gmail.com",
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Nous vous avons envoyé par mail le lien de réinitialisation du mot de passe !',
        ]);
    }

    public function testShowResetPasswordForm()
    {
         $this->artisan('migrate:fresh');
        $token = Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => "bamagid60@gmail.com",
            'token' => $token,
            'created_at' => now(),
        ]);
        $response = $this->get("/api/reset-password/$token");
        $response->assertStatus(200);
    }

    public function testSubmitResetPasswordForm()
    {
         $this->artisan('migrate:fresh');
        $token = Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => 'bamagid60@gmail.com',
            'token' => $token,
            'created_at' => now(),
        ]);
        $response = $this->post('/api/reset-password', [
            'password' => 'password_updated',
            'password_confirmation' => 'password_updated',
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Votre mot de passe a bien été réinitialisé !',
            ]);
    }
}
