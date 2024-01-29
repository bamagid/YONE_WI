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
        $this->authorize('create', Tarif::class);
        $tarif = new Tarif();
        $tarif->fill($request->validated());
        $tarif->reseau_id = $request->user()->reseau_id;
        $tarif->saveOrFail();
        return response()->json([
            "message" => "Le tarif a bien été enregistré",
            "tarif" => $tarif
        ], 201);
    }

    public function update(TarifRequest $request, Tarif $tarif)
    {
        $this->authorize("update", $tarif);
        $tarif->update($request->validated());
        return response()->json([
            "message" => "Le tarif a bien été mis à jour",
            "tarif" => $tarif
        ], 200);
    }

    public function destroy(Tarif $tarif)
    {
        $this->authorize("delete", $tarif);
        if ($tarif->etat === "actif") {
            $tarif->update(['etat' => 'corbeille']);
            return response()->json([
                "message" => "Le tarif a bien été mis dans la corbeille",
                "tarif" => $tarif
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvais mettre dans la corbeille que les tarifs actif",
        ], 422);
    }

    public function restore(Tarif $tarif)
    {
        $this->authorize("update", $tarif);
        if ($tarif->etat === "corbeille") {
            $tarif->update(['etat' => 'actif']);
            return response()->json([
                "message" => "Le tarif a bien été restauré",
                "tarif" => $tarif
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvais restaurer que les tarifs de la corbeille",
        ], 422);
    }
    public function delete(Tarif $tarif)
    {
        $this->authorize("delete", $tarif);
        if ($tarif->etat === "corbeille") {
            $tarif->update(['etat' => 'supprimé']);
            return response()->json([
                "message" => "Le tarif a bien été supprimé",
                "tarif" => $tarif
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }

    public function deleted()
    {
        $tarifsSupprimes = Tarif::where('etat', 'corbeille')->get();
        $this->authorize('view', $tarifsSupprimes);
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
            $this->authorize("delete", $tarif);
            $tarif->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille des tarifs a été vidée avec succès"
        ], 200);
    }
}
