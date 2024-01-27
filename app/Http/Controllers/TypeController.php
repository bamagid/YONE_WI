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
        $type->update($request->validated());
        return response()->json([
            "message" => "Le type a bien été mis à jour",
            "type" => $type
        ], 200);
    }

    public function destroy(Type $type)
    {
        $type->update(['etat' => 'corbeille']);
        return response()->json([
            "message" => "Le type a bien été mis dans la corbeille",
            "type" => $type
        ], 200);
    }


    public function restore(Type $type)
    {
        $type->update(['etat' => 'actif']);
        return response()->json([
            "message" => "Le type a bien été restauré",
            "type" => $type
        ], 200);
    }

    public function deleted()
    {
        $typesSupprimes = Type::where('etat', 'corbeille')->get();
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
            $type->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille des types a été vidée avec succès"
        ], 200);
    }
}
