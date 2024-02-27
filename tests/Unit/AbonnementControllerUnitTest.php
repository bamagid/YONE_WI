<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reseau;
use App\Models\Abonnement;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\Feature\UserControllerTest;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\AbonnementRequest;
use App\Http\Controllers\AbonnementController;

class AbonnementControllerUnitTest extends TestCase
{
    protected $abonnementController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->abonnementController = new AbonnementController();
        Artisan::call('migrate:fresh');
    }

    public static function createAbonnement()
    {
        Reseau::factory()->create();
        Abonnement::factory()->create();
    }

    public function testAbonnementUnitIndex()
    {
        AbonnementControllerUnitTest::createAbonnement();
        $response = $this->abonnementController->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La liste des abonnements actifs", $responseData['message']);
        $this->assertArrayHasKey('abonnements', $responseData);
    }

    public function testAbonnementUnitSubscribe()
    {
        $reseau = Reseau::factory()->create();
        $abonnement = Abonnement::factory(['reseau_id' => $reseau->id])->create();
        $response = $this->abonnementController->subscribe($abonnement);
        $numeroWhatsApp = $reseau->telephone;
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString("https://api.whatsapp.com/send?phone=$numeroWhatsApp", $response->getTargetUrl());
    }

    public function testMesAbonnementsUnit()
    {
        AbonnementControllerUnitTest::createAbonnement();
        UserControllerUnitTest::createUser();
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $response = $this->abonnementController->mesAbonnements();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La liste de mes abonnements actifs", $responseData['message']);
        $this->assertArrayHasKey('abonnements', $responseData);
    }

    public function testAbonnementUnitShow()
    {
        AbonnementControllerUnitTest::createAbonnement();
        $abonnement = Abonnement::first();
        $response = $this->abonnementController->show($abonnement);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Voici l'abonnement que vous recherchez", $responseData['message']);
        $this->assertArrayHasKey('abonnement', $responseData);
    }

    public function testAbonnementUnitStore()
    {
        UserControllerUnitTest::createUser();
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $abonnement = new AbonnementRequest([
            "prix" => 12000,
            "type" => "standard",
            "duree" => "mensuel",
            "description" => "Description d'un abonnement mensuel",
        ]);
        $abonnement->setContainer($this->app)->validateResolved();
        $abonnement->setUserResolver(function () use ($user) {
            return $user;
        });
        $response = $this->abonnementController->store($abonnement);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("L'abonnement a bien été enregistré", $responseData['message']);
        $this->assertArrayHasKey('abonnement', $responseData);
    }

    public function testAbonnementUnitUpdate()
    {
        UserControllerUnitTest::createUser();
        Abonnement::factory()->create();
        $abonnement = Abonnement::first();
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $abonnementupdate = new AbonnementRequest([
            "prix" => 15000,
            "type" => "premium",
            "duree" => "hebdomadaire",
            "description" => "Description d'un abonnement hebdomadaire",
        ]);
        $abonnementupdate->setContainer($this->app)->validateResolved();
        $abonnementupdate->setUserResolver(function () use ($user) {
            return $user;
        });
        $response = $this->abonnementController->update($abonnementupdate, $abonnement);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("L'abonnement a bien été mis à jour", $responseData['message']);
        $this->assertArrayHasKey('abonnement', $responseData);
    }

    public function testAbonnementUnitDestroy()
    {
        UserControllerUnitTest::createUser();
        Abonnement::factory()->create();
        $abonnement = Abonnement::first();
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $response = $this->abonnementController->destroy($abonnement);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("L'abonnement a bien été mis dans la corbeille", $responseData['message']);
        $this->assertArrayHasKey('abonnement', $responseData);
    }

    public function testAbonnementUnitDelete()
    {
        UserControllerUnitTest::createUser();
        Abonnement::factory()->create(["etat" => "corbeille"]);
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $abonnement = Abonnement::first();
        $response = $this->abonnementController->delete($abonnement);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("L'abonnement a bien été supprimé", $responseData['message']);
        $this->assertArrayHasKey('abonnement', $responseData);
    }

    public function testAbonnementUnitRestore()
    {
        UserControllerUnitTest::createUser();
        Abonnement::factory()->create(["etat" => "corbeille"]);
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $abonnement = Abonnement::first();
        $response = $this->abonnementController->restore($abonnement);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("L'abonnement a bien été restauré", $responseData['message']);
        $this->assertArrayHasKey('abonnement', $responseData);
    }

    public function testAbonnementUnitDeleted()
    {
        UserControllerUnitTest::createUser();
        Abonnement::factory()->create(["etat" => "corbeille"]);
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $response = $this->abonnementController->deleted();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La liste des abonnements dans la corbeille", $responseData['message']);
        $this->assertArrayHasKey('abonnements', $responseData);
    }

    public function testAbonnementUnitEmptyTrash()
    {
        UserControllerUnitTest::createUser();
        Abonnement::factory()->create(["etat" => "corbeille"]);
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $response = $this->abonnementController->emptyTrash();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La corbeille a été vidée avec succès", $responseData['message']);
    }
}
