<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Http\Requests\AbonnementRequest;

class AbonnementController extends Controller
{
    // Méthode pour afficher tous les abonnements
    public function index()
    {
        $abonnements = Abonnement::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des abonnements actifs",
            "abonnements" => $abonnements
        ], 200);
    }

    // Méthode pour afficher un abonnement spécifique
    public function show(Abonnement $abonnement)
    {
        return response()->json([
            "message" => "Voici l'abonnement que vous recherchez",
            "abonnement" => $abonnement
        ], 200);
    }

    // Méthode pour créer un nouvel abonnement
    public function store(AbonnementRequest $request)
    {
        $abonnement = Abonnement::create($request->validated());
        return response()->json([
            "message" => "L'abonnement a bien été enregistré",
            "abonnement" => $abonnement
        ], 201);
    }

    // Méthode pour mettre à jour un abonnement existant
    public function update(AbonnementRequest $request, Abonnement $abonnement)
    {
        $abonnement->update($request->validated());
        return response()->json([
            "message" => "L'abonnement a bien été mis à jour",
            "abonnement" => $abonnement
        ], 200);
    }

    // Méthode pour supprimer un abonnement (marquer comme "supprimé")
    public function destroy(Abonnement $abonnement)
    {
        $abonnement->update(['etat' => 'supprimé']);
        return response()->json([
            "message" => "L'abonnement a bien été supprimé",
            "abonnement" => $abonnement
        ]);
    }

    // Méthode pour restaurer un abonnement supprimé
    public function restore(Abonnement $abonnement)
    {
        $abonnement->update(['etat' => 'actif']);
        return response()->json([
            "message" => "L'abonnement a bien été restauré",
            "abonnement" => $abonnement
        ]);
    }
}
