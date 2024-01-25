<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Abonnement;
use App\Http\Requests\AbonnementRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AbonnementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {

        $response = $this->getJson('/api/abonnements');

        $response->assertStatus(200)
            ->assertJson([
                // Assurez-vous que la structure de la réponse est correcte
            ]);
    }

    public function testShow()
    {
        // Ajoutez un abonnement à la base de données

        $response = $this->getJson('/api/abonnements/1');

        $response->assertStatus(200)
            ->assertJson([
                // Assurez-vous que la structure de la réponse est correcte
            ]);
    }

    public function testStore()
    {
        // Créez des données de demande factices avec la classe AbonnementRequest

        $response = $this->postJson('/api/abonnements', $requestData);

        $response->assertStatus(201)
            ->assertJson([
                // Assurez-vous que la structure de la réponse est correcte
            ]);
    }

    // Répétez le processus pour les autres méthodes (update, destroy, restore, deleted, emptyTrash)
}
