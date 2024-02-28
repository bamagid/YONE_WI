<?php

use App\Http\Controllers\ReseauController;
use App\Http\Requests\DetailsReseauRequest;
use App\Http\Requests\ReseauRequest;
use Tests\TestCase;
use App\Models\User;
use App\Models\Reseau;
use App\Models\AdminSystem;
use Tests\Feature\UserControllerTest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReseauControllerUnitTest extends TestCase
{
    protected $reseaucontrolleur;
    protected function setUp(): void
    {
        parent::setUp();
        $this->reseaucontrolleur = new ReseauController();
        Artisan::call('migrate:fresh');
    }
    public function testReseauUnitIndex()
    {
        Reseau::factory()->create();
        $response = $this->reseaucontrolleur->index();
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('reseaux', $responseData);
        $this->assertEquals("La liste des reseaux actifs", $responseData['message']);
    }

    public function testReseauUnitShow()
    {

        $reseau = Reseau::factory()->create();
        $response = $this->reseaucontrolleur->show($reseau);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('reseau', $responseData);
        $this->assertEquals("Voici le reseau que vous recherchez", $responseData['message']);
    }

    public function testReseauUnitStore()
    {

        $admin = AdminSystem::factory()->create();
        $admin = $this->authenticatable(AdminSystem::class);
        $this->actingAs($admin, 'admin');
        $reseau = new ReseauRequest([
            "nom" => "dakar dem dikk"
        ]);
        $reseau->setContainer($this->app)->validateResolved();
        $response = $this->reseaucontrolleur->store($reseau);
        $this->assertEquals(201, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('reseau', $responseData);
        $this->assertEquals("Le reseau a bien été enregistré", $responseData['message']);
    }

    public function testReseauUnitUpdate()
    {

        $reseau = Reseau::factory()->create(["etat" =>"actif"]);
        $admin = AdminSystem::factory()->create();
        $admin = $this->authenticatable(AdminSystem::class);
        $this->actingAs($admin, 'admin');
        $reseauupdate = new ReseauRequest([
            "nom" => "dakar dem dikk 1"
        ]);
        $reseauupdate->setContainer($this->app)->validateResolved();
        $response = $this->reseaucontrolleur->update($reseauupdate, $reseau);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('reseau', $responseData);
        $this->assertEquals("Le reseau a bien été mis à jour", $responseData['message']);
    }
    public function Details()
    {

        UserControllerTest::createUser();
        User::factory()->create();
        $details = new DetailsReseauRequest([
            'description' => "La description de mon reseau",
            "telephone" => 778552211,
            "email" => null,
        ]);
        $user = $this->authenticatable(User::class);

        $details->setContainer($this->app)->validateResolved();
        $details->setUserResolver(function () use ($user) {
            return $user;
        });
        $response = $this->actingAs($user)->reseaucontrolleur->details($details);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('reseau', $responseData);
        $this->assertEquals("Le reseau a bien été mis à jour", $responseData['message']);
    }

    public function testReseauUnitDestroy()
    {

        AdminSystem::factory()->create();
        $reseau = Reseau::factory()->create(["etat" => "actif"]);
        $admin = $this->authenticatable(AdminSystem::class);
        $response = $this->actingAs($admin, 'admin')->reseaucontrolleur->destroy($reseau);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('reseau', $responseData);
        $this->assertEquals("Le reseau a bien été mis dans la corbeille", $responseData['message']);
    }

    public function testReseauUnitDelete()
    {

        AdminSystem::factory()->create();
        $reseau = Reseau::factory()->create(["etat" => "corbeille"]);
        $admin = $this->authenticatable(AdminSystem::class);
        $response = $this->actingAs($admin, 'admin')->reseaucontrolleur->delete($reseau);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('reseau', $responseData);
        $this->assertEquals("Le reseau a bien été supprimé", $responseData['message']);
    }

    public function testReseauUnitRestore()
    {

        AdminSystem::factory()->create();
        $reseau = Reseau::factory()->create(["etat" => "corbeille"]);
        $admin = $this->authenticatable(AdminSystem::class);
        $response = $this->actingAs($admin, 'admin')->reseaucontrolleur->restore($reseau);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('reseau', $responseData);
        $this->assertEquals("Le reseau a bien été restauré", $responseData['message']);
    }

    public function testReseauUnitDeleted()
    {

        Reseau::factory()->create(["etat" => "corbeille"]);
        AdminSystem::factory()->create();
        $admin = $this->authenticatable(AdminSystem::class);
        $response = $this->actingAs($admin, 'admin')->reseaucontrolleur->deleted();

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('reseaux', $responseData);
        $this->assertEquals("La liste des reseaux qui son dans la corbeilles", $responseData['message']);
    }

    public function testReseauUnitEmptyTrash()
    {

        Reseau::factory()->create(["etat" => "corbeille"]);
        AdminSystem::factory()->create();
        $admin = $this->authenticatable(AdminSystem::class);
        $response = $this->actingAs($admin, 'admin')->reseaucontrolleur->emptyTrash();
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La corbeille a été vidée avec succès", $responseData['message']);
    }
}
