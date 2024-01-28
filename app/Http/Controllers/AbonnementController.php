<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Http\Requests\AbonnementRequest;

class AbonnementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show', 'subscribe');
    }

    public function index()
    {
        $abonnements = Abonnement::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des abonnements actifs",
            "abonnements" => $abonnements
        ], 200);
    }

    public function show(Abonnement $abonnement)
    {
        if ($abonnement->etat === "supprimé") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Abonnement] $abonnement->id"
            ], 404);
        }
        return response()->json([
            "message" => "Voici l'abonnement que vous recherchez",
            "abonnement" => $abonnement
        ], 200);
    }

    public function store(AbonnementRequest $request)
    {
        $abonnement = new Abonnement();
        $abonnement->fill($request->validated());
        $abonnement->reseau_id = $request->user()->reseau_id;
        $abonnement->saveOrFail();
        return response()->json([
            "message" => "L'abonnement a bien été enregistré",
            "abonnement" => $abonnement
        ], 201);
    }
    public function subscribe(Abonnement $abonnement)
    {
        $numeroWhatsApp = $abonnement->reseau->telephone;
        $messageWhatsappEnvoye = "https://api.whatsapp.com/send?phone=$numeroWhatsApp";
        return redirect()->to($messageWhatsappEnvoye);
    }

    public function update(AbonnementRequest $request, Abonnement $abonnement)
    {
        $abonnement->update($request->validated());
        return response()->json([
            "message" => "L'abonnement a bien été mis à jour",
            "abonnement" => $abonnement
        ], 200);
    }

    public function destroy(Abonnement $abonnement)
    {
        if ($abonnement->etat === "actif") {
            $abonnement->update(['etat' => 'corbeille']);
            return response()->json([
                "message" => "L'abonnement a bien été mis dans la corbeille",
                "abonnement" => $abonnement
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvais mettre dans la corbeille que les abonnements actif",
        ], 422);
    }
    public function delete(Abonnement $abonnement)
    {
        if ($abonnement->etat === "corbeille") {
            $abonnement->update(['etat' => 'supprimé']);
            return response()->json([
                "message" => "L'abonnement a bien été supprimé",
                "abonnement" => $abonnement
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }

    public function restore(Abonnement $abonnement)
    {
        if ($abonnement->etat === "corbeille") {
            $abonnement->update(['etat' => 'actif']);
            return response()->json([
                "message" => "L'abonnement a bien été restauré",
                "abonnement" => $abonnement
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvais restaurer que les abonnements de la corbeille",
        ], 422);
    }

    public function deleted()
    {
        $abonnementsSupprimes = Abonnement::where('etat', 'corbeille')->get();
        if (empty($abonnementsSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de abonnements supprimés"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des abonnements qui se trouvent dans la corbeille",
            "abonnements" => $abonnementsSupprimes
        ], 200);
    }

    public function emptyTrash()
    {
        $abonnementsSupprimes = Abonnement::where('etat', 'corbeille')->get();
        if (empty($abonnementsSupprimes)) {
            return response()->json([
                "error" => "Il n'y a pas de abonnements supprimés"
            ], 404);
        }
        foreach ($abonnementsSupprimes as $abonnement) {
            $abonnement->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
