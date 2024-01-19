<?php

namespace App\Http\Controllers;

use App\Models\AdminSystem;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\StoreAdminSystemRequest;
use App\Http\Requests\UpdateAdminSystemRequest;

class AdminSystemController extends Controller
{


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminSystemRequest $request, AdminSystem $adminSystem)
    {
        $adminSystemupdate = $adminSystem->update($request->validated());
        return response()->json([
            "message" => "information mis a jour avec succés",
            'adminreseau' => $adminSystemupdate
        ],);
    }
    public function login(Request $request)
    {

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        $token = auth('admin')->attempt([
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
