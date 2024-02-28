<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Type;
use App\Models\User;
use App\Models\Ligne;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LigneRequest;
use Tests\Unit\UserControllerUnitTest;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\LigneController;
use App\Models\Reseau;

class LigneControllerUnitTest extends TestCase
{
    protected $ligneController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ligneController = new LigneController();
        Artisan::call('migrate:fresh');
    }

    protected static function createLigne()
    {
        Type::factory(2)->create();
        Ligne::factory()->create();
    }

    public function testLigneUnitIndex()
    {
        Reseau::factory()->create();
        LigneControllerUnitTest::createLigne();
        $response = $this->ligneController->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La liste des lignes actives", $responseData['message']);
        $this->assertArrayHasKey('lignes', $responseData);
    }

    public function testMesLignes()
    {
        UserControllerUnitTest::createUser();
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        LigneControllerUnitTest::createLigne();
        $response = $this->ligneController->mesLignes();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La liste de mes lignes actives", $responseData['message']);
        $this->assertArrayHasKey('lignes', $responseData);
    }

    public function testLigneUnitShow()
    {
        Reseau::factory()->create();
        LigneControllerUnitTest::createLigne();
        $ligne = Ligne::first();
        $response = $this->ligneController->show($ligne);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Voici la ligne que vous recherchez", $responseData['message']);
        $this->assertArrayHasKey('ligne', $responseData);
    }

    public function testLigneUnitStore()
    {
        UserControllerUnitTest::createUser();
        Type::factory()->create();
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $ligne = new LigneRequest([
            "nom" => "100A",
            "type_id" => 1,
            'lieuDepart' => "dakar",
            'lieuArrivee' => "mermoz"
        ]);
        $ligne->setContainer($this->app)->validateResolved();
        $ligne->setUserResolver(function () use ($user) {
            return $user;
        });
        $response = $this->ligneController->store($ligne);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La ligne a bien été enregistrée", $responseData['message']);
        $this->assertArrayHasKey('ligne', $responseData);
    }

    public function testLigneUnitUpdate()
    {
        UserControllerUnitTest::createUser();
        Type::factory(2)->create();
        Ligne::factory()->create();
        $ligne = Ligne::first();
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $ligneUpdate = new LigneRequest([
            "nom" => "100A",
            "type_id" => 1,
            'lieuDepart' => "dakar",
            'lieuArrivee' => "mermoz"
        ]);
        $ligneUpdate->setContainer($this->app)->validateResolved();
        $ligneUpdate->setUserResolver(function () use ($user) {
            return $user;
        });
        $response = $this->ligneController->update($ligneUpdate, $ligne);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La ligne a bien été mise à jour", $responseData['message']);
        $this->assertArrayHasKey('ligne', $responseData);
    }

    public function testLigneUnitDestroy()
    {
        UserControllerUnitTest::createUser();
        Type::factory(2)->create();
        Ligne::factory()->create();
        $ligne = Ligne::first();
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $response = $this->ligneController->destroy($ligne);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La ligne a bien été mise dans la  corbeille", $responseData['message']);
        $this->assertArrayHasKey('ligne', $responseData);
    }

    public function testLigneUnitDelete()
    {
        UserControllerUnitTest::createUser();
        Type::factory(2)->create();
        Ligne::factory()->create(["etat" => "corbeille"]);
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $ligne = Ligne::first();
        $response = $this->ligneController->delete($ligne);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La ligne a bien été supprimée", $responseData['message']);
        $this->assertArrayHasKey('ligne', $responseData);
    }

    public function testLigneUnitRestore()
    {
        UserControllerUnitTest::createUser();
        Type::factory(2)->create();
        Ligne::factory()->create(["etat" => "corbeille"]);
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $ligne = Ligne::first();
        $response = $this->ligneController->restore($ligne);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La ligne a bien été restaurée", $responseData['message']);
        $this->assertArrayHasKey('ligne', $responseData);
    }

    public function testLigneUnitDeleted()
    {
        UserControllerUnitTest::createUser();
        Type::factory(2)->create();
        Ligne::factory()->create(["etat" => "corbeille"]);
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $response = $this->ligneController->deleted();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La liste des lignes qui se trouve dans la corbeille", $responseData['message']);
        $this->assertArrayHasKey('lignes', $responseData);
    }

    public function testLigneUnitEmptyTrash()
    {
        UserControllerUnitTest::createUser();
        Type::factory(2)->create();
        Ligne::factory()->create(["etat" => "corbeille"]);
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $response = $this->ligneController->emptyTrash();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La corbeille a été vidée avec succès", $responseData['message']);
    }
}
