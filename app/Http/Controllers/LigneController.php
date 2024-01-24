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

    // Méthode pour supprimer une ligne (marquer comme "supprimé")
    public function destroy(Ligne $ligne)
    {
        $ligne->update(['etat' => 'supprimé']);
        return response()->json([
            "message" => "La ligne a bien été supprimée",
            "ligne" => $ligne
        ], 200);
    }

    // Méthode pour restaurer une ligne supprimée
    public function restore(Ligne $ligne)
    {
        $ligne->update(['etat' => 'actif']);
        return response()->json([
            "message" => "La ligne a bien été restaurée",
            "ligne" => $ligne
        ], 200);
    }
}
