<?php

namespace Tests\Unit;

use App\Http\Controllers\AdminSystemController;
use App\Http\Requests\ContactRequest;
use Tests\TestCase;
use App\Models\Contact;
use App\Models\AdminSystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\ContactController;

class ContactControllerUnitTest extends TestCase
{
    protected $contactController;
    protected $adminSystemController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contactController = new ContactController();
        $this->adminSystemController = new AdminSystemController();
        Artisan::call('migrate:fresh');
    }

    public function testContactUnitIndex()
    {
        Contact::factory(3)->create();
        AdminSystem::factory()->create();
        $admin = $this->authenticatable(AdminSystem::class);
        $response = $this->actingAs($admin, 'admin')->contactController->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("voici la liste des messages", $responseData['message']);
        $this->assertArrayHasKey('contact', $responseData);
    }

    public function testContactUnitAdmin()
    {
        AdminSystem::factory()->create();
        $response = $this->adminSystemController->showContact();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('email', $responseData);
        $this->assertArrayHasKey('telephone', $responseData);
    }

    public function testContactUnitStore()
    {
        $data = new ContactRequest([
            'email' => 'test@exemple.com',
            'sujet' => 'Test sujet du message de contact',
            'contenu' => 'Test contenu du  message de contact',
        ]);
        $data->setContainer($this->app)->validateResolved();
        $response = $this->contactController->store($data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Votre message a bien été envoyé. Nous vous contacterons bientôt.", $responseData['message']);
        $this->assertArrayHasKey('contact', $responseData);
    }
}
