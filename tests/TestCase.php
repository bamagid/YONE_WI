<?php

namespace Tests;

use App\Models\User;
use App\Models\AdminSystem;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static function getUserConstTest()
    {
        return User::factory()->create([
            "nom" => "Diouf",
            "prenom" => "Germaine",
            "adresse" => "dakar",
            "telephone" => "775392600",
            "email" => "dioufgermaine@gmail.com",
            "etat" => "actif",
            "role_id" => 1,
            "reseau_id" => 1,
            'email_verified_at' => now(),
            'password' => Hash::make('Password1@'),
            'remember_token' => Str::random(10),
        ]);
    }
    protected  static function migrateFresh()
    {
        Artisan::call('migrate:fresh');
    }

    protected static function authenticatable($className)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("La classe '$className' n'existe pas.");
        }

        // Instanciez la classe et trouvez un utilisateur
        $userClass = new \ReflectionClass($className);
        $user = $userClass->newInstance();

        return $user->find(1);
    }
}
