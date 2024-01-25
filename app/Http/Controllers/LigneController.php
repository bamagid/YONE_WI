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

    // Méthode pour afficher une ligne spécifique
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

    // Méthode pour créer une nouvelle ligne
    public function store(LigneRequest $request)
    {
        $ligne = Ligne::create($request->validated());
        return response()->json([
            "message" => "La ligne a bien été enregistrée",
            "ligne" => $ligne
        ], 201);
    }

    // Méthode pour mettre à jour une ligne existante
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
        return response()->json([
            "message" => "La liste des lignes qui se trouve dans la corbeille",
            "lignes" => $lignesSupprimees
        ], 200);
    }

    public function emptyTrash()
    {
        $lignesSupprimes = Ligne::where('etat', 'corbeille')->get();
        foreach ($lignesSupprimes as $ligne) {
            $ligne->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
