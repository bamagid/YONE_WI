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
    public function index()
    {
        $roles = Role::all();
        return response()->json([
            "message" => "La liste des roles disponible",
            "roles" => $roles
        ]);
    }


    public function store(RoleRequest $request)
    {
        $role = Role::create($request->validated());
        return response()->json([
            "message" => "Le role a bien été crée",
            "role" => $role
        ], 201);
    }


    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->validated());
        return response()->json([
            'message' => 'Role mise à jour avec succés !',
            "role" => $role
        ], 200);
    }
    public function destroy(Role $role)
    {
        $role->update(["etat" => "corbeille"]);
        return response()->json([
            "message" => "Le role a bien été mis dans la corbeille",
            "role" => $role
        ]);
    }
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
    public function restore(Role $role)
    {
        $role->update(["etat" => "actif"]);
        return response()->json([
            "message" => "Le role a bien été restauré",
            "role" => $role
        ]);
    }

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
