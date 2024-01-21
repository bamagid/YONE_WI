<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('multiauth')->except('store', 'login');
    }
    public function store(StoreUserRequest $request)
    {
        Role::FindOrFail($request->role_id);
        Reseau::FindOrFail($request->reseau_id);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images', $imageName, 'public');
        }
        $user = User::create([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
            "email" => $request->email,
            "role_id" => $request->role_id,
            "image" => $imagePath,
            "reseau_id" => $request->reseau_id,
            "password" => Hash::make($request->password)
        ]);
        return response()->json([
            "status" => true,
            "message" => "Bienvenue dans la communauté ",
            "user" => $user
        ], 201);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $imagePath = null;
        if ($request->file('image')) {

            if (File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images', $imageName, 'public');
        }
        dd($request);
        $user->update([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "adresse" => $request->adresse,
            "telephone" => $request->telephone,
            "email" => $request->email,
            "image" => $imagePath,
            "password" => Hash::make($request->password)
        ]);
        return response()->json([
            "status" => true,
            "message" => "Modification effectué avec succés",
            "user" => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        $token = auth('admin')->attempt($request->only('email', 'password'));
        $user = auth('admin')->user();
        $typeUser = "admin";
        if (empty($token)) {
            $token = auth('api')->attempt($request->only('email', 'password'));
            $user = auth('api')->user();
            $typeUser = "utilisateur";
        }
        if (!empty($token)) {

            return response()->json([
                "status" => true,
                "type" => $typeUser,
                "message" => "Bienvenue dans votre espace personnele, vous êtes connecté en tant qu'$typeUser ",
                "user" => $user,
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
        $user = auth('api')->user();
        if ($user === null) {
            $user = auth('admin')->user();
        }
        return response()->json([
            "status" => true,
            "message" => "Informations de profil",
            "user" => $user
        ], 200);
    }


    public function refreshToken()
    {
        $nouveauToken = auth('api')->user();
        if ($nouveauToken === null) {
            $nouveauToken = auth('admin')->user();
        }
        return response()->json([
            "status" => true,
            "message" => "Votre nouveau token",
            "token" => $nouveauToken
        ], 200);
    }


    public function logout()
    {
        if (auth('admin')->check()) {
            auth('admin')->logout();
        } elseif (auth('api')->check()) {
            auth('api')->logout();
        }
        return response()->json([
            "status" => true,
            "message" => "Utilisateur deconnecté avec succés"
        ], 200);
    }
    public function destroy(User $user)
    {
        $user->update(["etat" => "supprimé"]);
        return response()->json(["Le user a bien été supprimé"]);
    }
    public function changerEtat(User $user)
    {
        $user->update(['etat' => $user->etat === 'actif' ? 'bloqué' : 'actif']);
        return response()->json(["Le user a bien été restoré"]);
    }
}
