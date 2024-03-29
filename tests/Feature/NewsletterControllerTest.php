<?php

use Tests\TestCase;
use App\Models\Newsletter;
use App\Models\AdminSystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;

class NewsletterControllerTest extends TestCase
{
    use WithFaker;
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');
    }
    public function testSubscribe()
    {
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
        $subscriber = Newsletter::factory()->create(['etat' => 'abonné', "email" => $this->faker->unique()->safeEmail]);

        $response = $this->post('/api/newsletter/unscribe', [
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
