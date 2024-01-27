<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Http\Requests\TarifRequest;

class TarifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }

    public function index()
    {
        $tarifs = Tarif::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des tarifs actifs",
            "tarifs" => $tarifs
        ], 200);
    }


    public function show(Tarif $tarif)
    {
        if ($tarif->etat == "supprimé") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Tarif] $tarif->id"
            ], 404);
        }
        return response()->json([
            "message" => "Voici le tarif que vous recherchez",
            "tarif" => $tarif
        ], 200);
    }

    public function store(TarifRequest $request)
    {
        $tarif = Tarif::create($request->validated());
        return response()->json([
            "message" => "Le tarif a bien été enregistré",
            "tarif" => $tarif
        ], 201);
    }

    public function update(TarifRequest $request, Tarif $tarif)
    {
        $tarif->update($request->validated());
        return response()->json([
            "message" => "Le tarif a bien été mis à jour",
            "tarif" => $tarif
        ], 200);
    }

    public function destroy(Tarif $tarif)
    {
        $tarif->update(['etat' => 'corbeille']);
        return response()->json([
            "message" => "Le tarif a bien été mis dans la corbeille",
            "tarif" => $tarif
        ], 200);
    }

    public function restore(Tarif $tarif)
    {
        $tarif->update(['etat' => 'actif']);
        return response()->json([
            "message" => "Le tarif a bien été restauré",
            "tarif" => $tarif
        ], 200);
    }

    public function deleted()
    {
        $tarifsSupprimes = Tarif::where('etat', 'corbeille')->get();
        if (empty($tarifsSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de tarifs supprimés"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des tarifs qui sont dans la corbeille",
            "tarifs" => $tarifsSupprimes
        ], 200);
    }

    public function emptyTrash()
    {
        $tarifsSupprimes = Tarif::where('etat', 'corbeille')->get();
        if (empty($tarifsSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de tarifs supprimés"
            ], 404);
        }
        foreach ($tarifsSupprimes as $tarif) {
            $tarif->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille des tarifs a été vidée avec succès"
        ], 200);
    }
}
