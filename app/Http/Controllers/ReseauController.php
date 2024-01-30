<?php

namespace App\Http\Controllers;

use App\Models\Reseau;
use App\Http\Requests\ReseauRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReseauController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index', 'show', 'description');
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
        $reseaux = Reseau::where('etat', 'actif')->get();
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
        $reseau->update($request->validated());
        return response()->json([
            "message" => "Le reseau a bien été mise à jour",
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
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="telephone", type="integer"),
     *                     @OA\Property(property="email", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de reseaux"},
     * ),
     */
    public function details(Request $request)
    {
        $reseau = Reseau::FindOrFail($request->user()->reseau_id);
        $this->authorize("update", $reseau);
        $validator = Validator::make($request->all(), [
            'description' => ['nullable', 'string'],
            "telephone" => ['nullable', 'regex:/^(77|78|76|70|75|33)[0-9]{7}$/', 'unique:users,telephone'],
            "email" => ['nullable', 'email', 'unique:users,email'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $reseau->update($validator->validated());
        return response()->json([
            "message" => "Le reseau a bien été mise à jour",
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
        $reseauxSupprimes = Reseau::where('etat', 'corbeille')->get();
        if (empty($reseauxSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de réseaux supprimés"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des reseaux quui son dans la corbeilles",
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
        if (empty($reseausSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de réseaux supprimés"
            ], 404);
        }
        foreach ($reseausSupprimes as $reseau) {
            $reseau->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
