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
        $role = Role::create($request->validated());
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
        $role->update($request->validated());
        return response()->json([
            'message' => 'Role mise à jour avec succés !',
            "role" => $role
        ], 200);
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
