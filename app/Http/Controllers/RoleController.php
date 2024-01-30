<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * @OA\GET(
     *     path="/api/roles",
     *     summary="Lister les roles",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des roles"},

     * ),
     */

    public function index()
    {
        $roles = Role::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des roles disponible",
            "roles" => $roles
        ]);
    }
    /**
     * @OA\POST(
     *     path="/api/roles",
     *     summary="Ajouter un role",
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
     *     tags={"Gestion des roles"},

     * ),
     */

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->validated());
        return response()->json([
            "message" => "Le role a bien été crée",
            "role" => $role
        ], 201);
    }

    /**
     * @OA\PUT(
     *     path="/api/roles/{role}",
     *     summary="Modifier un role",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="role", required=false, @OA\Schema(type="string"),
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
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des roles"},

     * ),
     */

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->validated());
        return response()->json([
            'message' => 'Role mise à jour avec succés !',
            "role" => $role
        ], 200);
    }
    /**
     * @OA\PUT(
     *     path="/api/roles/delete/{role}",
     *     summary="supprimer un role dans la corbeille",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="role", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des roles"},

     * ),
     */
    public function destroy(Role $role)
    {
        if ($role->etat === "actif") {
            $role->update(["etat" => "corbeille"]);
            return response()->json([
                "message" => "Le role a bien été mis dans la corbeille",
                "role" => $role
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvais mettre dans la corbeille que les roles actif",
        ], 422);
    }
    /**
     * @OA\DELETE(
     *     path="/api/roles/{role}",
     *     summary="supprimé un role",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="204", description="Deleted successfully"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     * @OA\Response(response="404", description="Not Found"),
     *     @OA\Parameter(in="path", name="role", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des roles"},

     * ),
     */
    public function delete(Role $role)
    {
        if ($role->etat === "corbeille") {
            $role->update(['etat' => 'supprimé']);
            return response()->json([
                "message" => "Le role a bien été supprimé",
                "role" => $role
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }
    /**
     * @OA\PUT(
     *     path="/api/roles/restaurer/{role}",
     *     summary="restaurer",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="role", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des roles"},

     * ),
     */

    public function restore(Role $role)
    {
        if ($role->etat === "corbeille") {
            $role->update(["etat" => "actif"]);
            return response()->json([
                "message" => "Le role a bien été restauré",
                "role" => $role
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvais restaurer que les roles de la corbeille",
        ], 422);
    }

    /**
     * @OA\GET(
     *     path="/api/roles/deleted",
     *     summary="Lister les roles qui sont dans la corbeille",
     *     description="",
     *  security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des roles"},

     * ),
     */
    public function deleted()
    {
        $rolesSupprimes = Role::where('etat', 'corbeille')->get();
        if (empty($rolesSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de lignes supprimés"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des roles qui sont dans la corbeille ",
            "roles" => $rolesSupprimes
        ], 200);
    }
    /**
     * @OA\POST(
     *     path="/api/roles/empty-trash",
     *     summary="vider les roles qui sont dans la corbeille",
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
     *     tags={"Gestion des roles"},

     * ),
     */
    public function emptyTrash()
    {
        $rolesSupprimes = Role::where('etat', 'corbeille')->get();
        if (empty($rolesSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de lignes supprimés"
            ], 404);
        }
        foreach ($rolesSupprimes as $role) {
            $role->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille des roles a été vidée avec succès"
        ], 200);
    }
}
