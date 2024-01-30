<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Http\Requests\SectionRequest;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }


    /**
     * @OA\GET(
     *     path="/api/sections",
     *     summary="Lister les sections",
     *     description="",
     * * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des sections"},
     * ),
     */
    public function index()
    {
        $sections = Section::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des sections actives",
            "sections" => $sections
        ], 200);
    }


    /**
     * @OA\GET(
     *     path="/api/sections/{section}",
     *     summary="Afficher un section",
     *     description="",
     * * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des sections"},
     * ),
     */
    public function show(Section $section)
    {
        if ($section->etat == "supprimé") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Section] $section->id"
            ], 404);
        }
        return response()->json([
            "message" => "Voici la section que vous recherchez",
            "section" => $section
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/sections",
     *     summary="Ajouter un section",
     *     description="",
     * * security={
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
     *                     @OA\Property(property="Depart", type="string"),
     *                     @OA\Property(property="Arrivee", type="string"),
     *                     @OA\Property(property="ligne_id", type="integer"),
     *                     @OA\Property(property="tarif_id", type="integer"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des sections"},
     * ),
     */
    public function store(SectionRequest $request)
    {
        $this->authorize('create', Section::class);
        $section = new Section();
        $section->fill($request->validated());
        $section->created_by = $request->user()->email;
        $section->created_at = now();
        return response()->json([
            "message" => "La section a bien été enregistrée",
            "section" => $section
        ], 201);
    }

    /**
     * @OA\PATCH(
     *     path="/api/sections/{section}",
     *     summary="Modifier un section",
     *     description="",
     * * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string"),
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
     *                     @OA\Property(property="Depart", type="string"),
     *                     @OA\Property(property="Arrivee", type="string"),
     *                     @OA\Property(property="ligne_id", type="integer"),
     *                     @OA\Property(property="tarif_id", type="integer"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des sections"},
     * ),
     */

    public function update(SectionRequest $request, Section $section)
    {
        $this->authorize("update", $section);
        $section->fill($request->validated());
        $section->updated_by = $request->user()->email;
        $section->updated_at = now();
        $section->update();
        return response()->json([
            "message" => "La section a bien été mise à jour",
            "section" => $section
        ], 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/sections/{section}",
     *     summary="Supprimer un section",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="204", description="Deleted successfully"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     * @OA\Response(response="404", description="Not Found"),
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des sections"},
     * ),
     */

    public function destroy(Section $section)
    {
        $this->authorize("delete", $section);
        if ($section->etat === "actif") {
            $section->update(['etat' => 'corbeille']);
            return response()->json([
                "message" => "La section a bien été mis dans la corbeille",
                "section" => $section
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvais mettre dans la corbeille que les sections actif",
        ], 422);
    }
    /**
     * @OA\PATCH(
     *     path="/api/sections/delete/{section}",
     *     summary="supprimer  un section de la corbeille",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des sections"},
     * ),
     */
    public function delete(Section $section)
    {
        $this->authorize("delete", $section);
        if ($section->etat === "corbeille") {
            $section->update(['etat' => 'supprimé']);
            return response()->json([
                "message" => "La section a bien été supprimé",
                "section" => $section
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }

    /**
     * @OA\PATCH(
     *     path="/api/sections/restaurer/{section}",
     *     summary="restaurer un section",
     *     description="",
     * * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des sections"},
     * ),
     */
    public function restore(Section $section)
    {
        $this->authorize("restore", $section);
        if ($section->etat === "corbeille") {
            $section->update(['etat' => 'actif']);
            return response()->json([
                "message" => "La section a bien été restaurée",
                "section" => $section
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvais restaurer que les sections de la corbeille",
        ], 422);
    }


    /**
     * @OA\GET(
     *     path="/api/sections/deleted/all",
     *     summary="Lister les sections qui sont dans la corbeille",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des sections"},
     * ),
     */

    public function deleted()
    {
        $sectionsSupprimees = Section::where('etat', 'corbeille')->get();
        if (empty($sectionsSupprimees)) {
            return response()->json([
                "error" => "Il n'y a pas de sections supprimées"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des sections qui sont misent dans la corbeille",
            "sections" => $sectionsSupprimees
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/sections/empty-trash",
     *     summary="vider les sections qui sont dans la corbeille",
     *     description="",
     * * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des sections"},
     * ),
     */
    public function emptyTrash()
    {
        $sectionsSupprimees = Section::where('etat', 'corbeille')->get();
        if (empty($sectionsSupprimees)) {
            return response()->json([
                "error" => "Il n'y a pas de sections supprimées"
            ], 404);
        }
        foreach ($sectionsSupprimees as $section) {
            $section->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
