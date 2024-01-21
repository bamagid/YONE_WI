<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create(["nom" => $request->nom]);
        return response()->json([
            "message" => "Le role a bien été crée",
            "role" => $role
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $roleupdate = $role->update($request->validated());
        if ($roleupdate) {
            return response()->json([
                'message' => 'Mise à jour réussie !'
            ], 200);
        } else {
            return response()->json(['message' => 'Une erreur est survenue lors de la mise a jour']);
        }
    }
    public function destroy(Role $role)
    {
        $role->update(["etat" => "supprimé"]);
        return response()->json([
            "message" => "Le role a bien été supprimé",
            "role" => $role
        ]);
    }
    public function restore(Role $role)
    {
        $role->update(["etat" => "actif"]);
        return response()->json([
            "message" => "Le role a bien été restauré",
            "role" => $role
        ]);
    }
}
