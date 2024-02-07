<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Http\Requests\AbonnementRequest;
use App\Models\Historique;

class AbonnementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show', 'subscribe');
    }

    /**
     * @OA\GET(
     *     path="/api/abonnements",
     *     summary="Lister les abonnements",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des abonnements"},
     * ),
     */
    public function index()
    {
        $abonnements = Abonnement::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des abonnements actifs",
            "abonnements" => $abonnements
        ], 200);
    }
    /**
     * @OA\GET(
     *     path="/api/mesabonnements",
     *     summary="Lister mes abonnements",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des abonnements"},
     * ),
     */
    public function mesabonnements()
    {
        $abonnements = Abonnement::where('etat', 'actif')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();
        return response()->json([
            "message" => "La liste de mes abonnements actifs",
            "abonnements" => $abonnements
        ], 200);
    }

    /**
     * @OA\GET(
     *     path="/api/abonnements/{abonnement}",
     *     summary="Afficher un abonnement",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des abonnements"},
     * ),
     */
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

    /**
     * @OA\POST(
     *     path="/api/abonnements",
     *     summary="Ajouter un abonnements",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="prix", type="integer"),
     *                     @OA\Property(property="type", type="string"),
     *                     @OA\Property(property="duree", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des abonnements"},
     * ),
     */

    public function store(AbonnementRequest $request)
    {
        $this->authorize('create', Abonnement::class);
        $abonnement = new Abonnement();
        $abonnement->fill($request->validated());
        $abonnement->reseau_id = $request->user()->reseau_id;
        $abonnement->created_by = $request->user()->email;
        $abonnement->created_at = now();
        $abonnement->saveOrFail();
        Historique::enregistrerHistorique(
            'abonnements',
            $abonnement->id,
            $request->user()->id,
            'create',
            $request->user()->email,
            $request->user()->reseau->nom,
            null,
            json_encode($abonnement->toArray())
        );
        return response()->json([
            "message" => "L'abonnement a bien été enregistré",
            "abonnement" => $abonnement
        ], 201);
    }
    /**
     * @OA\GET(
     *     path="/api/abonnements/subscribe/{abonnement}",
     *     summary="souscrire a un abonnement",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *  @OA\Parameter(in="path", name="abonnement", required=true, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des abonnements"},
     * ),
     */
    public function subscribe(Abonnement $abonnement)
    {
        $numeroWhatsApp = $abonnement->reseau->telephone;
        $messageWhatsappEnvoye = "https://api.whatsapp.com/send?phone=$numeroWhatsApp";
        return redirect()->to($messageWhatsappEnvoye);
    }

    /**
     * @OA\PATCH(
     *     path="/api/abonnements/{abonnement}",
     *     summary="Modifier un abonnements",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="prix", type="integer"),
     *                     @OA\Property(property="type", type="string"),
     *                     @OA\Property(property="duree", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des abonnements"},
     * ),
     */

    public function update(AbonnementRequest $request, Abonnement $abonnement)
    {
        if ($abonnement->etat !== "actif") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Abonnement] $abonnement->id"
            ], 404);
        }
        $valeurAvant = $abonnement->toArray();
        $this->authorize('update', $abonnement);
        $abonnement->fill($request->validated());
        $abonnement->updated_by = $request->user()->email;
        $abonnement->updated_at = now();
        Historique::enregistrerHistorique(
            'abonnements',
            $abonnement->id,
            $request->user()->id,
            'update',
            auth()->user()->email,
            auth()->user()->reseau->nom,
            json_encode($valeurAvant),
            json_encode($abonnement->toArray())
        );
        $abonnement->update();
        return response()->json([
            "message" => "L'abonnement a bien été mis à jour",
            "abonnement" => $abonnement
        ], 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/abonnements/{abonnement}",
     *     summary="Supprimer un abonnement",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="204", description="Deleted successfully"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     * @OA\Response(response="404", description="Not Found"),
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des abonnements"},
     * ),
     */
    public function destroy(Abonnement $abonnement)
    {
        $valeurAvant = $abonnement->toArray();
        $this->authorize('delete', $abonnement);
        if ($abonnement->etat === "actif") {
            Historique::enregistrerHistorique(
                'abonnements',
                $abonnement->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($abonnement->toArray())
            );
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
    /**
     * @OA\PATCH(
     *     path="/api/abonnements/delete/{abonnement}",
     *     summary="supprimer un abonnement de la corbeille",
     *     description="",
     *security={
     *   {"BearerAuth": {}},
     *},     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des abonnements"},
     * ),
     */
    public function delete(Abonnement $abonnement)
    {
        $valeurAvant = $abonnement->toArray();
        $this->authorize('delete', $abonnement);
        if ($abonnement->etat === "corbeille") {
            Historique::enregistrerHistorique(
                'abonnements',
                $abonnement->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($abonnement->toArray())
            );
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

    /**
     * @OA\PATCH(
     *     path="/api/abonnements/restaurer/{abonnement}",
     *     summary="restaurer un abonnement",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des abonnements"},
     * ),
     */
    public function restore(Abonnement $abonnement)
    {
        $valeurAvant = $abonnement->toArray();
        $this->authorize('restore', $abonnement);
        if ($abonnement->etat === "corbeille") {
            Historique::enregistrerHistorique(
                'abonnements',
                $abonnement->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($abonnement->toArray())
            );
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

    /**
     * @OA\GET(
     *     path="/api/abonnements/deleted/all",
     *     summary="Lister les abonnements qui sont dans la corbeille",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des abonnements"},
     * ),
     */
    public function deleted()
    {
        $abonnementsSupprimes = Abonnement::where('etat', 'corbeille')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();
        if ($abonnementsSupprimes->all() == null) {
            return response()->json([
                "message" => "Il n'y a pas d'abonnements dans la corbeille"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des abonnements qui se trouvent dans la corbeille",
            "abonnements" => $abonnementsSupprimes
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/abonnements/empty-trash",
     *     summary="vider les abonnements qui sont dans la corbeille",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des abonnements"},
     * ),
     */
    public function emptyTrash()
    {
        $abonnementsSupprimes = Abonnement::where('etat', 'corbeille')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();

        if ($abonnementsSupprimes->all() == null) {
            return response()->json([
                "message" => "Il n'y a pas d'abonnements dans la corbeille"
            ], 404);
        }
        foreach ($abonnementsSupprimes as $abonnement) {
            $valeurAvant = $abonnement->toArray();
            $this->authorize('delete', $abonnement);
            Historique::enregistrerHistorique(
                'abonnements',
                $abonnement->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($abonnement->toArray())
            );
            $abonnement->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
