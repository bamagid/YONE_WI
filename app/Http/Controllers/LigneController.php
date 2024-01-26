<?php

namespace App\Http\Controllers;

use App\Models\Ligne;
use App\Http\Requests\LigneRequest;

class LigneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user')->except('index', 'show');
    }
    // Méthode pour afficher toutes les lignes
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
        $ligne = Ligne::create($request->validated());
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
        $ligne->update(['etat' => 'corbeille']);
        return response()->json([
            "message" => "La ligne a bien été mise dans la  corbeillee",
            "ligne" => $ligne
        ], 200);
    }

    public function restore(Ligne $ligne)
    {
        $ligne->update(['etat' => 'actif']);
        return response()->json([
            "message" => "La ligne a bien été restaurée",
            "ligne" => $ligne
        ], 200);
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
