<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('multiauth')->except('store', 'login');
    }
    public function index()
    {
        $users = User::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des types actifs",
            "types" => $users
        ], 200);
    }
    public function store(StoreUserRequest $request)
    {
        Role::FindOrFail($request->role_id);
        Reseau::FindOrFail($request->reseau_id);
        $user = new User();
        $user->fill($request->validated());
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images', $imageName, 'public');
            $user->image = $imagePath;
        }
        $user->save();
        $user->notify(new SendEmailVerificationNotification);
        return response()->json([
            "status" => true,
            "message" => "Bienvenue dans la communauté ",
            "user" => $user
        ], 201);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->fill($request->validated());
        $imagePath = null;
        if ($request->file('image')) {

            if (File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images', $imageName, 'public');
            $user->image = $imagePath;
        }
        $user->update();
        return response()->json([
            "status" => true,
            "message" => "Modification effectué avec succés",
            "user" => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $token = auth('admin')->attempt($request->only('email', 'password'));
        $user = auth('admin')->user();
        $typeUser = "admin";
        if (empty($token)) {
            $token = auth('api')->attempt($request->only('email', 'password'));
            $user = auth('api')->user();
            $typeUser = "utilisateur";
        }
        if (!empty($token) && (empty($user->etat) || $user->etat == "actif")) {

            return response()->json([
                "status" => true,
                "type" => $typeUser,
                "message" => "Bienvenue dans votre espace personnele, vous êtes connecté en tant qu'$typeUser ",
                "user" => $user,
                "token" => $token
            ], 200);
        } elseif (!empty($user->etat) &&  $user->etat == "bloqué") {
            return response()->json([
                "status" => false,
                "message" => "Votre compte a été bloqué par l'administrateur",
                "motif" => $user->motif
            ]);
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
    public function destroy(Request $request, User $user)
    {

        $validator = Validator::make(
            $request->all(),
            [
                "motif" => ["required", "string"],
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $user->update([
            "etat" => "supprimé",
            "motif" => $request->motif
        ]);
        return response()->json(["Le user a bien été supprimé"]);
    }
    public function changerEtat(Request $request, User $user)
    {
        if ($user->etat === "actif") {
            $validator = Validator::make(
                $request->all(),
                [
                    "motif" => ["required", "string"],
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            $user->update([
                "motif" => $request->motif,
            ]);
        }
        $user->update(['etat' => $user->etat === 'actif' ? 'bloqué' : 'actif']);
        $statut = $user->etat === 'actif' ? 'restoré' : 'bloqué';
        return response()->json(["Le user a bien été $statut"]);
    }
}
