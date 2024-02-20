<?php

use Tests\TestCase;
use App\Models\Role;
use App\Models\Type;
use App\Models\User;
use App\Models\Reseau;
use App\Models\Abonnement;
use Tests\Feature\UserControllerTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AbonnementControllerTest extends TestCase
{
    public function __construct(){
        $this->migrateFresh();
    }
    public function testAbonnementIndex()
    {
        $response = $this->get('/api/abonnements');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnements" => $response->json('abonnements')
        ]);
    }

    public function testAbonnementSubscribe()
    {
        $reseau = Reseau::factory()->create();
        $abonnement = Abonnement::factory(['reseau_id' => $reseau->id])->create();
        $response = $this->get("/api/abonnements/subscribe/{$abonnement->id}");
        $numeroWhatsApp = $reseau->telephone;
        $response->assertRedirect("https://api.whatsapp.com/send?phone=$numeroWhatsApp");
    }
    public function testMesAbonnements()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();
        Abonnement::factory(3)->create();
        $response = $this->actingAs($user)->get('/api/mesabonnements');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnements" => $response->json('abonnements')
        ]);
    }

    public function testAbonnementShow()
    {
        UserControllerTest::createUser();
        $abonnement = Abonnement::factory()->create();
        $response = $this->get("/api/abonnements/$abonnement->id");
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testAbonnementStore()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/abonnements', [
            "prix" => 10000,
            "type" => "luxe",
            "duree" => "annuel",
            "description" => "Descritption d'un abonnement annuel",
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testAbonnementUpdate()
    {
        UserControllerTest::createUser();
        $abonnement = Abonnement::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/abonnements/$abonnement->id", [
            "prix" => 10000,
            "type" => "luxe modifier",
            "duree" => "annuel modifié",
            "description" => "Descritption d'un abonnement annuel modifié",
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testAbonnementDestroy()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $abonnement = Abonnement::factory()->create(["etat" => "actif"]);
        $response = $this->actingAs($user)->delete("/api/abonnements/$abonnement->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testAbonnementDelete()
    {
        UserControllerTest::createUser();
        $abonnement = Abonnement::factory()->create(["etat" => "corbeille"]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/api/abonnements/delete/{$abonnement->id}");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testAbonnementRestore()
    {
        UserControllerTest::createUser();
        $user = User::factory()->create();
        $abonnement = Abonnement::factory()->create(["etat" => "corbeille"]);
        $response = $this->actingAs($user)->patch("/api/abonnements/restaurer/$abonnement->id");

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnement" => $response->json('abonnement')
        ]);
    }

    public function testAbonnementDeleted()
    {
        UserControllerTest::createUser();
        Abonnement::factory(3)->create(["etat" => "corbeille"]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/abonnements/deleted/all');

        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message'),
            "abonnements" => $response->json('abonnements')
        ]);
    }

    public function testAbonnementEmptyTrash()
    {
        UserControllerTest::createUser();
        Abonnement::factory(3)->create(["etat" => "corbeille"]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/api/abonnements/empty-trash');
        $response->assertStatus(200);
        $response->assertJson([
            "message" => $response->json('message')
        ]);
    }
}
