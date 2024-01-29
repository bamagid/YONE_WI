<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Http\Requests\TypeRequest;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    public function index()
    {
        $types = Type::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des types actifs",
            "types" => $types
        ], 200);
    }

    public function store(TypeRequest $request)
    {
        $this->authorize('create', Type::class);
        $type = Type::create($request->validated());
        return response()->json([
            "message" => "Le type a bien été enregistré",
            "type" => $type
        ], 201);
    }

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

    public function update(TypeRequest $request, Type $type)
    {
        $this->authorize("update", $type);
        $type->update($request->validated());
        return response()->json([
            "message" => "Le type a bien été mis à jour",
            "type" => $type
        ], 200);
    }

    public function destroy(Type $type)
    {
        $this->authorize("delete", $type);
        if ($type->etat === "actif") {
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

    public function delete(Type $type)
    {
        $this->authorize("delete", $type);
        if ($type->etat === "corbeille") {
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
    public function restore(Type $type)
    {
        $this->authorize("update", $type);
        if ($type->etat === "corbeille") {
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

    public function deleted()
    {
        $typesSupprimes = Type::where('etat', 'corbeille')->get();
        $this->authorize("view", $typesSupprimes);
        if (empty($typesSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de types supprimés"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des types qui sont dans la corbeille",
            "types" => $typesSupprimes
        ], 200);
    }

    public function emptyTrash()
    {
        $typesSupprimes = Type::where('etat', 'corbeille')->get();
        if (empty($typesSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de types supprimés"
            ], 404);
        }
        foreach ($typesSupprimes as $type) {
            $this->authorize("delete", $type);
            $type->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille des types a été vidée avec succès"
        ], 200);
    }
}
