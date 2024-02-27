<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Reseau;
use App\Models\Historique;
use Illuminate\Support\Str;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\MotifRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('multiauth')->only('update', 'profile', 'refreshtoken', 'logout');
        $this->middleware('auth:admin')->only('store', 'destroy', 'changerEtat', 'index', 'usersblocked');
    }
    /**
     * @OA\GET(
     *     path="/api/users",
     *     summary="Lister les utilisateurs",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des utilisateurs"},
     * ),
     */
    public function index()
    {
        $users = Cache::rememberForever('users_actifs', function () {
            return User::where('etat', 'actif')->get();
        });
        return response()->json([
            "message" => "La liste des utilisateurs actifs",
            "users" => $users
        ], 200);
    }
    /**
     * @OA\GET(
     *     path="/api/users/blocked",
     *     summary="Lister les utilisateurs bloqués",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des utilisateurs"},
     * ),
     */
    public function usersblocked()
    {
        $users = Cache::rememberForever('users_bloqués', function () {
            return User::where('etat', 'bloqué')->get();
        });
        return $users->isEmpty() ?

            response()->json([
                "message" => "Il n'y a pas d'utilisateurs bloqués",
            ], 200)
            :
            response()->json([
                "message" => "La liste des utilisateurs bloqués",
                "users" => $users
            ], 200);
    }
    /**
     * @OA\POST(
     *     path="/api/users",
     *     summary="Creation d'un admin reseau",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthorized"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="prenom", type="string"),
     *                     @OA\Property(property="adresse", type="string"),
     *                     @OA\Property(property="telephone", type="string"),
     *                     @OA\Property(property="role_id", type="string"),
     *                     @OA\Property(property="reseau_id", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="image", type="string", format="binary"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des utilisateurs"},
     * ),
     */
    public function store(StoreUserRequest $request)
    {
        Role::FindOrFail($request->role_id);
        Reseau::FindOrFail($request->reseau_id);
        $user = new User();
        $password = Str::random(12);
        $user->password = Hash::make($password);
        $user->fill($request->validated());
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $user->image = $image->store('images', 'public');
        }
        $user->save();
        Mail::send('emaillogin', [
            'username' => $user->email,
            'password' => $password
        ], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Notification d\'inscription sur le site');
        });
        Cache::forget('users_actifs');
        Cache::forget('users_bloqués');
        return response()->json([
            "status" => true,
            "message" => "Bienvenue dans la communauté ",
            "user" => $user
        ], 201);
    }
    /**
     * @OA\POST(
     *     path="/api/users/{user}",
     *     summary="Modification de compte",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthorized"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="path", name="user", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="prenom", type="string"),
     *                     @OA\Property(property="adresse", type="string"),
     *                     @OA\Property(property="telephone", type="integer"),
     *                     @OA\Property(property="reseau_id", type="string"),
     *                     @OA\Property(property="image", type="string", format="binary"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="old_password", type="string"),
     *                     @OA\Property(property="password", type="string"),
     *                     @OA\Property(property="password_confirmation", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de compte"},
     * ),
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($user->etat !== "actif") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\User] $user->id"
            ], 404);
        }
        if ($request->old_password && Hash::check($request->old_password, $user->password) === false) {
            return response()->json([
                "error" => "Ancien mot de passe incorrect"
            ], 422);
        }

        $valeurAvant = $user->toArray();
        $id_reseau = $user->reseau_id;
        $user->fill($request->validated());
        if ($request->file('image')) {

            if (File::exists(storage_path($user->image))) {
                File::delete(storage_path($user->image));
            }
            $image = $request->file('image');
            $user->image = $image->store('images', 'public');
        }
        if ($request->user() && $request->user()->role_id === 1) {
            $user->reseau_id == $id_reseau;
        }
        Historique::enregistrerHistorique(
            'users',
            $user->id,
            $user->id,
            'update',
            $user->email ? $user->exists :  $user->getOriginal('email'),
            $user->reseau->nom,
            json_encode($valeurAvant),
            json_encode($user->toArray())
        );
        Cache::forget('users_bloqués');
        $user->update();
        return response()->json([
            "status" => true,
            "message" => "Modification effectué avec succés",
            "user" => $user
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/login",
     *     summary="connexion",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthorized"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="password", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de l'authentification"},
     * ),
     */

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
        if (!empty($user->etat) &&  $user->etat !== "actif") {
            auth('api')->logout();
            return response()->json([
                "status" => false,
                "message" => "Votre compte a été $user->etat par l'administrateur",
                "motif" => $user->motif
            ], 401);
        } elseif (!empty($token)) {

            return response()->json([
                "status" => true,
                "type" => $typeUser,
                "message" => "Bienvenue dans votre espace personnele, vous êtes connecté en tant qu'$typeUser ",
                "user" => $user,
                "token" => $token,
                "expires_in" => "3600 seconds"
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Les informations fournis sont incorrect"
        ], 401);
    }

    /**
     * @OA\GET(
     *     path="/api/profile",
     *     summary="afficher l'utilisateur connecté ",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion de compte"},
     * ),
     */
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
    /**
     * @OA\GET(
     *     path="/api/refresh",
     *     summary="Rafraichir le token",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion de compte"},
     * ),
     */

    public function refreshToken()
    {
        $guard = auth('api')->check() ? "api" : "admin";
        $nouveauToken = auth($guard)->refresh();

        return response()->json([
            "status" => true,
            "message" => "Votre nouveau token",
            "token" => $nouveauToken
        ], 200);
    }

    /**
     * @OA\GET(
     *     path="/api/logout",
     *     summary="deconnexion",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion de compte"},
     * ),
     */

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
    /**
     * @OA\PATCH(
     *     path="/api/users/{user}",
     *     summary="supprimé",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="401", description="Unauthorized"),
     * @OA\Response(response="403", description="Unauthorize"),
     * @OA\Response(response="404", description="Not Found"),
     *     @OA\Parameter(in="path", name="user", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="motif", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des utilisateurs"},
     * ),
     */
    public function destroy(MotifRequest $request, User $user)
    {
        if ($user->etat !== "suprimé") {
            $user->update([
                "etat" => "supprimé",
                "motif" => $request->motif
            ]);
            Historique::enregistrerHistorique(
                'users',
                $user->id,
                $user->id,
                'update',
                $user->email,
                $user->reseau->nom,
                null,
                null,
                $request->motif
            );
            Cache::forget('users_bloqués');
            return response()->json(["message" => "Le user a bien été supprimé"]);
        }
        return response()->json([
            "message" => "No query results for model [App\\Models\\User] $user->id"
        ], 404);
    }
    /**
     * @OA\PATCH(
     *     path="/api/users/etat/{user}",
     *     summary="Bloquer ou debloquer  un utilisateur",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="user", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="motif", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des utilisateurs"},
     * ),
     */

    public function changerEtat(MotifRequest  $request, User $user)
    {
        switch ($user->etat) {
            case 'actif':
                $user->update([
                    "etat" => "bloqué",
                    "motif" => $request->motif,
                ]);
                Mail::send('blockuser', ['user' => $user], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Notification de blockage  d\'un compte sur le site');
                });
                Historique::enregistrerHistorique(
                    'users',
                    $user->id,
                    $user->id,
                    'update',
                    $user->email,
                    $user->reseau->nom,
                    null,
                    null,
                    $request->motif
                );
                Cache::forget('users_bloqués');
                return response()->json(["message" => "Le user a bien été bloqué "]);
            case 'bloqué':
                $user->update(['etat' => 'actif']);
                Mail::send('unblockuser', ['user' => $user], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Notification de déblockage  d\'un compte sur le site');
                });
                Historique::enregistrerHistorique(
                    'users',
                    $user->id,
                    $user->id,
                    'update',
                    $user->email,
                    $user->reseau->nom,
                    null,
                    null,
                    'deblocage du compte'
                );
                Cache::forget('users_bloqués');
                return response()->json(["message" => "Le user a bien été debloqué"]);
            default:
                return response()->json([
                    "message" => "No query results for model [App\\Models\\User] $user->id"
                ], 404);
        }
    }
}
