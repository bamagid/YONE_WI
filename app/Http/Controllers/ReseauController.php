<?php

namespace App\Http\Controllers;

use App\Models\Reseau;
use App\Http\Requests\StoreReseauRequest;
use App\Http\Requests\UpdateReseauRequest;

class ReseauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reseaux = Reseau::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des reseaux actifs",
            "reseaux" => $reseaux
        ], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReseauRequest $request)
    {

        $reseau = Reseau::create(["nom" => $request->nom]);
        return response()->json([
            "message" => "Le reseau a bien été enregistré",
            "reseau" => $reseau
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reseau $reseau)
    {
        return response()->json([
            "message" => "voici le reseau que vous rechercher",
            "reseau" => $reseau
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReseauRequest $request, Reseau $reseau)
    {
        $reseauUpdate = $reseau->update($request->validated());
        return response()->json([
            "message" => "Le reseau a bien été mise a jour",
            "reseau" => $reseauUpdate
        ], 200);
    }
    public function delete(Reseau $reseau)
    {
        $reseau->update(['etat' => 'supprimé']);
    }
}
