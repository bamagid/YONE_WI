<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Http\Requests\TarifRequest;

class TarifController extends Controller
{
    // Méthode pour afficher tous les tarifs
    public function index()
    {
        $tarifs = Tarif::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des tarifs actifs",
            "tarifs" => $tarifs
        ], 200);
    }

    // Méthode pour afficher un tarif spécifique
    public function show(Tarif $tarif)
    {
        return response()->json([
            "message" => "Voici le tarif que vous recherchez",
            "tarif" => $tarif
        ], 200);
    }

    // Méthode pour créer un nouveau tarif
    public function store(TarifRequest $request)
    {
        $tarif = Tarif::create($request->validated());
        return response()->json([
            "message" => "Le tarif a bien été enregistré",
            "tarif" => $tarif
        ], 201);
    }

    // Méthode pour mettre à jour un tarif existant
    public function update(TarifRequest $request, Tarif $tarif)
    {
        $tarif->update($request->validated());
        return response()->json([
            "message" => "Le tarif a bien été mis à jour",
            "tarif" => $tarif
        ], 200);
    }

    // Méthode pour supprimer un tarif (marquer comme "supprimé")
    public function destroy(Tarif $tarif)
    {
        $tarif->update(['etat' => 'supprimé']);
        return response()->json([
            "message" => "Le tarif a bien été supprimé",
            "tarif" => $tarif
        ], 200);
    }

    // Méthode pour restaurer un tarif supprimé
    public function restore(Tarif $tarif)
    {
        $tarif->update(['etat' => 'actif']);
        return response()->json([
            "message" => "Le tarif a bien été restauré",
            "tarif" => $tarif
        ], 200);
    }
}
