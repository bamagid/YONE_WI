<?php

namespace App\Http\Controllers;

use App\Models\Ligne;
use App\Http\Requests\LigneRequest;

class LigneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }
    /**
     * @OA\GET(
     *     path="/api/lignes",
     *     summary="Lister les lignes",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */

    public function index()
    {
        $lignes = Ligne::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des lignes actives",
            "lignes" => $lignes
        ], 200);
    }

    /**
     * @OA\GET(
     *     path="/api/meslignes",
     *     summary="Lister mes lignes",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function meslignes()
    {
        $lignes = Ligne::where('etat', 'actif')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();
        return response()->json([
            "message" => "La liste de mes lignes actifs",
            "lignes" => $lignes
        ], 200);
    }


    /**
     * @OA\GET(
     *     path="/api/lignes/{ligne}",
     *     summary="Afficher une ligne",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function show(Ligne $ligne)
    {
        if ($ligne->etat == "supprimé") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Ligne] $ligne->id"
            ], 404);
        }
        return response()->json([
            "message" => "Voici la ligne que vous recherchez",
            "ligne" => $ligne
        ], 200);
    }


    /**
     * @OA\POST(
     *     path="/api/lignes",
     *     summary="Ajouter une ligne",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
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
     *                     @OA\Property(property="type", type="string"),
     *                     @OA\Property(property="type_id", type="integer"),
     *                     @OA\Property(property="lieuDepart", type="string"),
     *                     @OA\Property(property="lieuArrivee", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function store(LigneRequest $request)
    {
        $this->authorize('create', Ligne::class);
        $ligne = new Ligne();
        $ligne->fill($request->validated());
        $ligne->reseau_id = $request->user()->reseau_id;
        $ligne->created_by = $request->user()->email;
        $ligne->created_at = now();
        $ligne->save();
        return response()->json([
            "message" => "La ligne a bien été enregistrée",
            "ligne" => $ligne
        ], 201);
    }


    /**
     * @OA\PATCH(
     *     path="/api/lignes/{ligne}",
     *     summary="Modifier une ligne",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
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
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="type_id", type="integer"),
     *                     @OA\Property(property="lieuDepart", type="string"),
     *                     @OA\Property(property="lieuArrivee", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function update(LigneRequest $request, Ligne $ligne)
    {
        $this->authorize("update", $ligne);
        $ligne->fill($request->validated());
        $ligne->updated_by = $request->user()->email;
        $ligne->updated_at = now();
        $ligne->update();
        return response()->json([
            "message" => "La ligne a bien été mise à jour",
            "ligne" => $ligne
        ], 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/lignes/{ligne}",
     *     summary="Supprimer une ligne",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="204", description="Deleted successfully"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     * @OA\Response(response="404", description="Not Found"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function destroy(Ligne $ligne)
    {
        $this->authorize("delete", $ligne);
        if ($ligne->etat === "actif") {
            $ligne->update(['etat' => 'corbeille']);
            return response()->json([
                "message" => "La ligne a bien été mise dans la  corbeillee",
                "ligne" => $ligne
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvais mettre dans la corbeille que les lignes actif",
        ], 422);
    }
    /**
     * @OA\PATCH(
     *     path="/api/lignes/delete/{ligne}",
     *     summary="supprimer une ligne de la corbeille",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function delete(Ligne $ligne)
    {
        $this->authorize("delete", $ligne);
        if ($ligne->etat === "corbeille") {
            $ligne->update(['etat' => 'supprimé']);
            return response()->json([
                "message" => "La ligne a bien été supprimée",
                "ligne" => $ligne
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }

    /**
     * @OA\PATCH(
     *     path="/api/lignes/restaurer/{ligne}",
     *     summary="restaurer une ligne",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function restore(Ligne $ligne)
    {
        $this->authorize("restore", $ligne);
        if ($ligne->etat === "corbeille") {

            $ligne->update(['etat' => 'actif']);
            return response()->json([
                "message" => "La ligne a bien été restaurée",
                "ligne" => $ligne
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvais restaurer que les lignes de la corbeille",
        ], 422);
    }

    /**
     * @OA\GET(
     *     path="/api/lignes/deleted/all",
     *     summary="Lister les lignes qui sont dans la corbeille",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function deleted()
    {
        $lignesSupprimees = Ligne::where('etat', 'corbeille')->get();
        if (empty($lignesSupprimees)) {
            return response()->json([
                "error" => "Il n'y a pas de lignes supprimées"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des lignes qui se trouve dans la corbeille",
            "lignes" => $lignesSupprimees
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/lignes/empty-trash",
     *     summary="vider les lignes qui sont dans la corbeille",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function emptyTrash()
    {
        $lignesSupprimees = Ligne::where('etat', 'corbeille')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();
        if (empty($lignesSupprimees)) {
            return response()->json([
                "error" => "Il n'y a pas de lignes supprimées"
            ], 404);
        }
        foreach ($lignesSupprimees as $ligne) {
            $this->authorize("delete", $ligne);
            $ligne->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
