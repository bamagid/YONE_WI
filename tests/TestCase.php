<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected  function getUserConstTest()
    {
        return User::factory()->create([
            "nom" => "Diouf",
            "prenom" => "Germaine",
            "adresse" => "dakar",
            "telephone" => "775392600",
            "email" => "dioufgermainedaba@gmail.com",
            "role_id" => 1,
            "reseau_id" => 1,
            'email_verified_at' => now(),
            'password' => Hash::make('Password1@'),
            'remember_token' => Str::random(10),
        ]);
    }
    protected function migrateFresh()
    {
        $this->artisan('migrate:fresh');
    }
}
