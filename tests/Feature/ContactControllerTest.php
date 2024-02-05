<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use App\Models\AdminSystem;
use Illuminate\Foundation\Testing\WithFaker;

class ContactControllerTest extends TestCase
{
    public function testContactIndex()
    {
       $this->artisan('migrate:fresh');
        Contact::factory(3)->create();
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get('/api/contacts');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "contact" => $response->json('contact')
            ]);
    }
    public function testContactStore()
    {
       $this->artisan('migrate:fresh');
        $response = $this->post('/api/contacts', [
            'email' => 'test@exemple.com',
            'sujet' => 'Test sujet du message de contact',
            'contenu' => 'Test contenu du  message de contact',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                "message" => $response->json('message'),
                "contact" => $response->json('contact')
            ]);
    }
}
