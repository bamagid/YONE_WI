<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reseau;
use Illuminate\Http\Request;
use App\Events\ReseauUpdated;
use App\Http\Requests\ReseauRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\DetailsReseauRequest;

class ReseauController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index', 'show', 'details');
    }
    /**
     * @OA\GET(
     *     path="/api/reseaus",
     *     summary="Lister les reseaux",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function index()
    {
        $reseaux = Cache::rememberForever('reseaux_actifs', function () {
            return Reseau::where('etat', 'actif')->get();
        });

        return response()->json([
            "message" => "La liste des reseaux actifs",
            "reseaux" => $reseaux
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/reseaus",
     *     summary="Ajouter un reseau",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
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
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function store(ReseauRequest $request)
    {
        $reseau = Reseau::create($request->validated());
        Cache::forget('reseaux_actifs');
        Cache::forget('reseaux_supprimes');
        return response()->json([
            "message" => "Le reseau a bien été enregistré",
            "reseau" => $reseau
        ], 201);
    }
    /**
     * @OA\GET(
     *     path="/api/reseaus/{reseau}",
     *     summary="Afficher un reseau",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function show(Reseau $reseau)
    {
        if ($reseau->etat == "supprimé") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Reseau] $reseau->id"
            ], 404);
        }
        return response()->json([
            "message" => "Voici le reseau que vous recherchez",
            "reseau" => $reseau
        ], 200);
    }

    /**
     * @OA\PATCH(
     *     path="/api/reseaus/{reseau}",
     *     summary="Modifier un reseau",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function update(ReseauRequest $request, Reseau $reseau)
    {
        if ($reseau->etat !== "actif") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Reseau] $reseau->id"
            ], 404);
        }
        $reseau->update($request->validated());
        Cache::forget('reseaux_actifs');
        return response()->json([
            "message" => "Le reseau a bien été mis à jour",
            "reseau" => $reseau
        ], 200);
    }
    /**
     * @OA\PATCH(
     *     path="/api/reseau/details",
     *     summary="Modifier les details d'un reseau",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *  @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="telephone", type="integer"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="image", type="string", format="binary"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function details(DetailsReseauRequest $request)
    {
        $reseau = Reseau::FindOrFail($request->user()->reseau_id);
        $this->authorize("update", $reseau);
        if (auth()->user()->email !== $request->email && User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => "L'email que vous avez renseigné existe déjà, veuillez le modifier"
            ], 422);
        }
        $reseau->fill($request->validated());
        if ($request->file('image')) {

            if (File::exists(storage_path($reseau->image))) {
                File::delete(storage_path($reseau->image));
            }
            $image = $request->file('image');
            $reseau->image = $image->store('images', 'public');
        }
        $reseau->update();
        Cache::forget('reseaux_actifs');
        return response()->json([
            "message" => "Le reseau a bien été mis à jour",
            "reseau" => $reseau
        ], 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/reseaus/{reseau}",
     *     summary="Supprimer un reseau",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="204", description="Deleted successfully"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     * @OA\Response(response="404", description="Not Found"),
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function destroy(Reseau $reseau)
    {
        if ($reseau->etat === "actif") {
            $reseau->update(['etat' => 'corbeille']);
            Cache::forget('reseaux_actifs');
            Cache::forget('reseaux_supprimes');
            event(new ReseauUpdated($reseau));
            return response()->json([
                "message" => "Le reseau a bien été mis dans la corbeille",
                "reseau" => $reseau
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvais mettre dans la corbeille que les reseaux actif",
        ], 422);
    }
    /**
     * @OA\PATCH(
     *     path="/api/reseaus/delete/{reseau}",
     *     summary="supprimer un reseau de la corbeille",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function delete(Reseau $reseau)
    {
        if ($reseau->etat === "corbeille") {
            $reseau->update(['etat' => 'supprimé']);
            Cache::forget('reseaux_actifs');
            Cache::forget('reseaux_supprimes');
            event(new ReseauUpdated($reseau));
            return response()->json([
                "message" => "Le reseau a bien été supprimé",
                "reseau" => $reseau
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }
    /**
     * @OA\PATCH(
     *     path="/api/reseaus/restaurer/{reseau}",
     *     summary="restaurer un reseau",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function restore(Reseau $reseau)
    {
        if ($reseau->etat === "corbeille") {
            $reseau->update(['etat' => 'actif']);
            Cache::forget('reseaux_actifs');
            Cache::forget('reseaux_supprimes');
            return response()->json([
                "message" => "Le reseau a bien été restauré",
                "reseau" => $reseau
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvais restaurer que les reseaux de la corbeille",
        ], 422);
    }
    /**
     * @OA\GET(
     *     path="/api/reseaus/deleted/all",
     *     summary="Lister les reseaux qui sont dans la corbeille",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function deleted()
    {
        $reseauxSupprimes = Cache::rememberforever('reseaux_supprimes', function () {
            return Reseau::where('etat', 'corbeille')->get();
        });

        return $reseauxSupprimes->isEmpty()
            ? response()->json([
                "message" => "Il n'y a pas de réseaux dans la corbeille"
            ], 404)
            : response()->json([
                "message" => "La liste des reseaux qui son dans la corbeilles",
                "reseaux" => $reseauxSupprimes
            ], 200);
    }
    /**
     * @OA\POST(
     *     path="/api/reseaus/empty-trash",
     *     summary="vider les reseaux qui sont dans la corbeille",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion de reseaux"},
     * ),
     */

    public function emptyTrash()
    {
        $reseausSupprimes = Reseau::where('etat', 'corbeille')->get();
        if ($reseausSupprimes->all() == null) {
            return response()->json([
                "message" => "Il n'y a pas de réseaux dans la corbeille"
            ], 404);
        }
        foreach ($reseausSupprimes as $reseau) {
            $reseau->update(["etat" => "supprimé"]);
        }
        Cache::forget('reseaux_actifs');
        Cache::forget('reseaux_supprimes');
        event(new ReseauUpdated($reseau));
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
