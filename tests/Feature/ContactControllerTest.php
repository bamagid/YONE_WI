<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use App\Models\AdminSystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;

class ContactControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');
    }
    public function testContactIndex()
    {
        Contact::factory(3)->create();
        $admin = AdminSystem::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get('/api/contacts');
        $response->assertStatus(200)
            ->assertJson([
                "message" => $response->json('message'),
                "contact" => $response->json('contact')
            ]);
    }
    public function testContactAdmin()
    {
         AdminSystem::factory()->create();
        $response = $this->get('/api/contactsadmin');
        $response->assertStatus(200)
            ->assertJson([
                "email" => $response->json('email'),
                "telephone" => $response->json('telephone')
            ]);
    }
    public function testContactStore()
    {
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
