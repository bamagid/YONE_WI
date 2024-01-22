<?php

namespace App\Http\Controllers;

use App\Models\Reseau;
use App\Http\Requests\StoreReseauRequest;
use App\Http\Requests\UpdateReseauRequest;

class ReseauController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index', 'show');
    }
    public function index()
    {
        $reseaux = Reseau::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des reseaux actifs",
            "reseaux" => $reseaux
        ], 200);
    }


    public function store(StoreReseauRequest $request)
    {

        $reseau = Reseau::create(["nom" => $request->nom]);
        return response()->json([
            "message" => "Le reseau a bien été enregistré",
            "reseau" => $reseau
        ], 201);
    }

    public function show(Reseau $reseau)
    {
        dd($reseau);
        return response()->json([
            "message" => "voici le reseau que vous rechercher",
            "reseau" => $reseau
        ], 200);
    }


    public function update(UpdateReseauRequest $request, Reseau $reseau)
    {
        $reseau->update($request->validated());
        return response()->json([
            "message" => "Le reseau a bien été mise a jour",
            "reseau" => $reseau
        ], 200);
    }

    public function destroy(Reseau $reseau)
    {
        $reseau->update(['etat' => 'supprimé']);
        return response()->json([
            "message" => "Le reseau a bien été supprimé",
            "reseau" => $reseau
        ]);
    }
    public function restore(Reseau $reseau)
    {
        $reseau->update(['etat' => 'actif']);
        return response()->json([
            "message" => "Le reseau a bien été restauré",
            "reseau" => $reseau
        ]);
    }
}
