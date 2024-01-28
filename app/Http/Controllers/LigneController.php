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
    public function index()
    {
        $lignes = Ligne::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des lignes actives",
            "lignes" => $lignes
        ], 200);
    }


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


    public function store(LigneRequest $request)
    {
        $ligne = new Ligne();
        $ligne->fill($request->validated());
        $ligne->reseau_id = $request->user()->reseau_id;
        $ligne->save();
        return response()->json([
            "message" => "La ligne a bien été enregistrée",
            "ligne" => $ligne
        ], 201);
    }


    public function update(LigneRequest $request, Ligne $ligne)
    {
        $ligne->update($request->validated());
        return response()->json([
            "message" => "La ligne a bien été mise à jour",
            "ligne" => $ligne
        ], 200);
    }

    public function destroy(Ligne $ligne)
    {
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
    public function delete(Ligne $ligne)
    {
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

    public function restore(Ligne $ligne)
    {
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

    public function emptyTrash()
    {
        $lignesSupprimees = Ligne::where('etat', 'corbeille')->get();
        if (empty($lignesSupprimees)) {
            return response()->json([
                "error" => "Il n'y a pas de lignes supprimées"
            ], 404);
        }
        foreach ($lignesSupprimees as $ligne) {
            $ligne->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
