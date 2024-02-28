<?php

namespace Tests\Unit;

use App\Http\Requests\MotifRequest;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use App\Models\AdminSystem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tests\CreatesApplication;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Requests\UpdateUserRequest;
use Tests\Feature\UserControllerTest;

class UserControllerUnitTest extends TestCase
{
    use CreatesApplication;
    protected $UserController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->UserController = new UserController();
        Artisan::call('migrate:fresh');
    }
    public static function createUser()
    {
        Role::factory()->create();
        Reseau::factory()->create([
            "nom" => "aftu",
            'telephone' => 775511222
        ]);
        // Créez un utilisateur avec des identifiants valides
        User::factory()->create([
            "nom" => "Diouf",
            "prenom" => "Germaine",
            "adresse" => "dakar",
            "telephone" => "775392640",
            "email" => "dioufgermainedaba@gmail.com",
            "role_id" => 1,
            "reseau_id" => 1,
            'email_verified_at' => now(),
            'password' => Hash::make('Password1@'),
            'remember_token' => Str::random(10),
        ]);
    }

    public function testUnitLoginWithValidCredentials()
    {
        UserControllerUnitTest::createUser();
        // Envoyez une demande de connexion avec les identifiants valides
        $request = new LoginRequest([
            'email' => 'dioufgermainedaba@gmail.com',
            'password' => 'Password1@',
        ]);
        $response = $this->UserController->login($request);
        $this->assertArrayHasKey('status', $response->original);
        $this->assertArrayHasKey('type', $response->original);
        $this->assertArrayHasKey('message', $response->original);
        $this->assertArrayHasKey('user', $response->original);
        $this->assertArrayHasKey('token', $response->original);
        $this->assertArrayHasKey('expires_in', $response->original);
    }

    public function testLoginWithUserBlocked()
    {
        UserControllerUnitTest::createUser();
        // Créez un utilisateur avec des identifiants valides
        $user = $this->getUserConstTest();
        $user->update(['etat' => "bloqué"]);
        // Envoyez une demande de connexion avec les identifiants d'un utilisateur bloqué
        $request = new LoginRequest([
            'email' => 'dioufgermaine@gmail.com',
            'password' => 'Password1@',
        ]);
        $response = $this->UserController->login($request);

        // Assurez-vous que la réponse est de type JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assurez-vous que le code de statut HTTP est 401 (Unauthorized)
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertArrayHasKey('status', $response->original);
        $this->assertArrayHasKey('motif', $response->original);
        $this->assertArrayHasKey('message', $response->original);
    }


    public function testUnitRegisterUser()
    {
        // Créez une demande HTTP simulée avec des données valides
        $storeUserRequest = new StoreUserRequest();
        UserControllerUnitTest::createUser();
        $storeUserRequest->merge([
            "nom" => "Diouf",
            "prenom" => "Germaine",
            "adresse" => "dakar",
            "telephone" => "775392610",
            "email" => "dioufgermaine@gmail.com",
            "role_id" => 1,
            "reseau_id" => 1,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10)

        ]);
        // Déclenchez la validation et remplissez les données validées
        $storeUserRequest->setContainer($this->app)->validateResolved();

        $response = $this->UserController->store($storeUserRequest);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(201, $response->getStatusCode());


        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Bienvenue dans la communauté ', $responseData['message']);
        $this->assertArrayHasKey('user', $responseData);
    }
    public function testUnitUserUpdate()
    {
        UserControllerUnitTest::createUser();
        $updateUserRequest = new UpdateUserRequest();
        $updateUserRequest->merge([
            "nom" => "magid",
            "prenom" => "abdoul",
            "adresse" => "dakar",
            "telephone" => "778552240",
            "email" => "email1@gmail.com",
            "reseau_id" => 1,
            "role_id" => 1,
            "password" => "Password1@",
            "password_confirmation" => "Password1@"
        ]);

        // Déclenchez la validation et remplissez les données validées
        $updateUserRequest->setContainer($this->app)->validateResolved();
        $user = $this->getUserConstTest();
        $response = $this->UserController->update($updateUserRequest, $user);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());


        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Modification effectué avec succés", $responseData['message']);
        $this->assertArrayHasKey('user', $responseData);
    }

    public function testUnitUsersBlocked()
    {
        UserControllerUnitTest::createUser();
        $user = $this->getUserConstTest();
        $user->update(['etat' => "bloqué"]);
        $response = $this->UserController->usersblocked();
        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());


        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La liste des utilisateurs bloqués", $responseData['message']);
        $this->assertArrayHasKey('users', $responseData);
    }

    public function testUnitUserIndex()
    {
        UserControllerUnitTest::createUser();
        $response = $this->UserController->index();
        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());


        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("La liste des utilisateurs actifs", $responseData['message']);
        $this->assertArrayHasKey('users', $responseData);
    }


    public function testUnitProfile()
    {
        UserControllerUnitTest::createUser();
        $user = $this->authenticatable(User::class);
        $this->actingAs($user);
        $response = $this->UserController->profile();
        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());


        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Informations de profil", $responseData['message']);
        $this->assertArrayHasKey('user', $responseData);
        $this->assertArrayHasKey('status', $responseData);
    }
    public function testUnitLogout()
    {
        UserControllerUnitTest::createUser();
        $user = $this->authenticatable(User::class);
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->UserController->logout();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Utilisateur deconnecté avec succés", $responseData['message']);
        $this->assertArrayHasKey('status', $responseData);
    }

    public function testDestroyUser()
    {
        UserControllerTest::createUser();
        $user = $this->getUserConstTest();
        AdminSystem::factory()->create();
        $admin = $this->authenticatable(AdminSystem::class);
        $this->actingAs($admin, 'admin');
        $motif = new MotifRequest();
        $motif->merge(['motif' => 'motif pour supprimer un user']);
        $motif->setContainer($this->app)->validateResolved();
        $response = $this->UserController->destroy($motif, $user);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Le user a bien été supprimé", $responseData['message']);
    }

    public function testChangerEtat()
    {
        UserControllerTest::createUser();
        AdminSystem::factory()->create();
        $admin = $this->authenticatable(AdminSystem::class);
        $user = $this->getUserConstTest();
        $this->actingAs($admin, 'admin');
        $motif = new MotifRequest();
        $motif->merge(['motif' => 'motif pour bloquer']);
        $motif->setContainer($this->app)->validateResolved();
        $response = $this->UserController->changerEtat($motif, $user);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("Le user a bien été bloqué ", $responseData['message']);
    }
}
