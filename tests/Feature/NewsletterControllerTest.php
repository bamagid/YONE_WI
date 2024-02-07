<?php

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Newsletter;
use App\Models\AdminSystem;

class NewsletterControllerTest extends TestCase
{
    use WithFaker;
    public function testSubscribe()
    {
       $this->artisan('migrate:fresh');
        $response = $this->post('/api/newsletter/subscribe', [
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                "message" => $response->json('message'),
                "subscriber" => $response->json('subscriber')
            ]);
    }

    public function testUnscribe()
    {
        $this->artisan('migrate:fresh');

        $subscriber = Newsletter::factory()->create(['etat' => 'abonné', "email" => $this->faker->unique()->safeEmail]);

        $response = $this->patch('/api/newsletter/unscribe', [
            'email' => $subscriber->email,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "subscriber" => $response->json('subscriber')
            ]);
    }

    public function testShowSubscribers()
    {
        $this->artisan('migrate:fresh');
        $admin = AdminSystem::factory()->create();
        $this->actingAs($admin, 'admin');

        Newsletter::factory(3)->create();
        $response = $this->get('/api/newsletter/all');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "subscribers" => $response->json('subscribers')
        ]);
    }
}
