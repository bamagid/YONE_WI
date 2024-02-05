<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Historique;
use App\Http\Requests\TypeRequest;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }
    /**
     * @OA\GET(
     *     path="/api/types",
     *     summary="Lister les types",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion des types"},
     * ),
     */
    public function index()
    {
        $types = Type::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des types actifs",
            "types" => $types
        ], 200);
    }

    /**
     * @OA\GET(
     *     path="/api/mestypes",
     *     summary="Lister mes types",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des types"},
     * ),
     */
    public function mestypes()
    {
        $types = Type::where('etat', 'actif')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();
        return response()->json([
            "message" => "La liste de mes types actifs",
            "types" => $types
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/types",
     *     summary="Ajouter un type",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des types"},
     * ),
     */
    public function store(TypeRequest $request)
    {
        $this->authorize('create', Type::class);
        $type = new Type();
        $type->fill($request->validated());
        $type->created_by = $request->user()->email;
        $type->reseau_id = $request->user()->reseau_id;
        $type->created_at = now();
        $type->saveOrFail();
        Historique::enregistrerHistorique(
            'types',
            $type->id,
            auth()->user()->id,
            'create',
            auth()->user()->email,
            auth()->user()->reseau->nom,
            null,
            json_encode($type->toArray()),
        );
        return response()->json([
            "message" => "Le type a bien été enregistré",
            "type" => $type
        ], 201);
    }

    /**
     * @OA\GET(
     *     path="/api/types/{type}",
     *     summary="Afficher un type",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="type", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion des types"},
     * ),
     */
    public function show(Type $type)
    {
        if ($type->etat == "supprimé") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Type] $type->id"
            ], 404);
        }
        return response()->json([
            "message" => "Voici le type que vous recherchez",
            "type" => $type
        ], 200);
    }

    /**
     * @OA\Patch(
     *     path="/api/types/{type}",
     *     summary="Modifier un type",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="path", name="type", required=false, @OA\Schema(type="string"),
     *),
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
     *                     @OA\Property(property="description", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des types"},
     * ),
     */

    public function update(TypeRequest $request, Type $type)
    {
        if ($type->etat !== "actif") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Type] $type->id"
            ], 404);
        }
        $valeurAvant = $type->toArray();
        $this->authorize("update", $type);
        $type->fill($request->validated());
        $type->updated_by = $request->user()->email;
        $type->updated_at = now();
        Historique::enregistrerHistorique(
            'types',
            $type->id,
            auth()->user()->id,
            'update',
            auth()->user()->email,
            auth()->user()->reseau->nom,
            json_encode($valeurAvant),
            json_encode($type)
        );
        $type->update();
        return response()->json([
            "message" => "Le type a bien été mis à jour",
            "type" => $type
        ], 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/types/{type}",
     *     summary="Supprimer un type",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="204", description="Deleted successfully"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     * @OA\Response(response="404", description="Not Found"),
     *     @OA\Parameter(in="path", name="type", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion des types"},
     * ),
     */
    public function destroy(Type $type)
    {
        $valeurAvant = $type->toArray();
        $this->authorize("delete", $type);
        if ($type->etat === "actif") {
            Historique::enregistrerHistorique(
                'types',
                $type->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($type->toArray())
            );
            $type->update(['etat' => 'corbeille']);
            return response()->json([
                "message" => "Le type a bien été mis dans la corbeille",
                "type" => $type
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvais mettre dans la corbeille que les types actif",
        ], 422);
    }

    /**
     * @OA\PATCH(
     *     path="/api/types/delete/{type}",
     *     summary="supprimer un type de la corbeille",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="type", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion des types"},
     * ),
     */
    public function delete(Type $type)
    {
        $valeurAvant = $type->toArray();
        $this->authorize("delete", $type);
        if ($type->etat === "corbeille") {
            Historique::enregistrerHistorique(
                'types',
                $type->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($type->toArray())
            );
            $type->update(['etat' => 'supprimé']);
            return response()->json([
                "message" => "Le type a bien été supprimé",
                "type" => $type
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }

    /**
     * @OA\PATCH(
     *     path="/api/types/restaurer/{type}",
     *     summary="restaurer un type",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="type", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion des types"},
     * ),
     */
    public function restore(Type $type)
    {
        $valeurAvant = $type->toArray();
        $this->authorize("restore", $type);
        if ($type->etat === "corbeille") {
            Historique::enregistrerHistorique(
                'types',
                $type->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($type->toArray())
            );
            $type->update(['etat' => 'actif']);
            return response()->json([
                "message" => "Le type a bien été restauré",
                "type" => $type
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvais restaurer que les types de la corbeille",
        ], 422);
    }

    /**
     * @OA\GET(
     *     path="/api/types/deleted/all",
     *     summary="Lister les types qui sont dans la corbeille",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion des types"},
     * ),
     */
    public function deleted()
    {
        $typesSupprimes = Type::where('etat', 'corbeille')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();
        if ($typesSupprimes->all() == null) {
            return response()->json([
                "message" => "Il n'y a pas de types dans la corbeille"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des types qui sont dans la corbeille",
            "types" => $typesSupprimes
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/types/empty-trash",
     *     summary="vider les types qui sont dans la corbeille",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion des types"},
     * ),
     */
    public function emptyTrash()
    {
        $typesSupprimes = Type::where('etat', 'corbeille')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();
        if ($typesSupprimes->all() == null) {
            return response()->json([
                "message" => "Il n'y a pas de types dans la corbeille"
            ], 404);
        }
        foreach ($typesSupprimes as $type) {
            $valeurAvant = $type->toArray();
            Historique::enregistrerHistorique(
                'types',
                $type->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($type->toArray())
            );
            $type->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille des types a été vidée avec succès"
        ], 200);
    }
}
