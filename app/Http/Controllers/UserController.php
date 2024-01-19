<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                "nom" => "required",
                "prenom" => "required",
                "adresse" => "required",
                "telephone" => "required",
                "image" => "image|sometimes",
                "email" => "required|email|unique:users",
                "password" => "required"
            ]
        );

        $user = User::create([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
            "email" => $request->email,
            "role_id" => 1,
            "reseau_id" => 1,
            "password" => Hash::make($request->password)
        ]);
        return response()->json([
            "status" => true,
            "message" => "Bienvenue dans la communauté ",
            "user" => $user
        ], 201);
    }
    public function update(Request $request, User $user)
    {
        $request->validate(
            [
                "nom" => "required",
                "prenom" => "required",
                "adresse" => "required",
                "telephone" => "required",
                "image" => "image|sometimes",
                "email" => "required|email|unique:users",
                "password" => "required"
            ]
        );

        $user->update([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);
        return response()->json([
            "status" => true,
            "message" => "Bienvenue dans la communauté ",
            "user" => $user
        ], 201);
    }

    public function login(Request $request)
    {

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if (!empty($token)) {

            return response()->json([
                "status" => true,
                "message" => "Bienvenue dans votre espace ",
                "token" => $token
            ], 200);
        }

        return response()->json([
            "status" => false,
            "message" => "Les informations fournis sont incorrect"
        ], 401);
    }

    public function profile()
    {

        $user = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Informations de profil",
            "user" => $user
        ], 200);
    }


    public function refreshToken()
    {

        $nouveauToken = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "Votre nouveau token",
            "token" => $nouveauToken
        ], 200);
    }


    public function logout()
    {

        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "Utilisateur deconnecté avec succés"
        ], 200);
    }
}
