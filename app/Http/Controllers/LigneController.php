<?php

namespace App\Http\Controllers;

use App\Events\LigneUpdated;
use App\Models\Ligne;
use App\Models\Historique;
use App\Models\Newsletter;
use App\Http\Requests\LigneRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class LigneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }
    /**
     * @OA\GET(
     *     path="/api/lignes",
     *     summary="Lister les lignes",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */

    public function index()
    {
        $lignes = Cache::rememberForever('lignes_actifs', function () {
            return  Ligne::where('etat', 'actif')->get();
        });
        return response()->json([
            "message" => "La liste des lignes actives",
            "lignes" => $lignes
        ], 200);
    }

    /**
     * @OA\GET(
     *     path="/api/meslignes",
     *     summary="Lister mes lignes",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function meslignes()
    {
        $lignes = Cache::rememberForever('mes_lignes_actifs', function () {
            return Ligne::where('etat', 'actif')
                ->where('reseau_id', auth()->user()->reseau_id)
                ->get();
        });

        return $lignes->isEmpty() ?
            response()->json([
                "message" => "Vous n'avez pas de lignes actifs"
            ])
            :
            response()->json([
                "message" => "La liste de mes lignes actifs",
                "lignes" => $lignes
            ], 200);
    }


    /**
     * @OA\GET(
     *     path="/api/lignes/{ligne}",
     *     summary="Afficher une ligne",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
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


    /**
     * @OA\POST(
     *     path="/api/lignes",
     *     summary="Ajouter une ligne",
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
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="type_id", type="integer"),
     *                     @OA\Property(property="lieuDepart", type="string"),
     *                     @OA\Property(property="lieuArrivee", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function store(LigneRequest $request)
    {
        $this->authorize('create', Ligne::class);
        $lignes_reseau = Ligne::where('reseau_id', $request->user()->reseau_id)->get();
        if ($lignes_reseau->contains('nom', $request->validated()['nom'])) {
            return response()->json([
                'message' => 'Desolé cette ligne existe déjà dans votre reseau '
            ], 422);
        }
        $ligne = new Ligne();
        $ligne->fill($request->validated());
        $ligne->reseau_id = $request->user()->reseau_id;
        $ligne->created_by = $request->user()->email;
        $ligne->created_at = now();
        $ligne->save();
        Historique::enregistrerHistorique(
            'lignes',
            $ligne->id,
            auth()->user()->id,
            'create',
            auth()->user()->email,
            auth()->user()->reseau->nom,
            null,
            json_encode($ligne->toArray())
        );
        $users = Newsletter::where('etat', 'abonné')->get();
        foreach ($users as $user) {
            Mail::send('newsletterligne', ['user' => $user, 'ligne' => $ligne], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Notification d\'ajout d\'une nouvelle ligne sur le site');
            });
        }

        return response()->json([
            "message" => "La ligne a bien été enregistrée",
            "ligne" => $ligne
        ], 201);
    }


    /**
     * @OA\PATCH(
     *     path="/api/lignes/{ligne}",
     *     summary="Modifier une ligne",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
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
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="type_id", type="integer"),
     *                     @OA\Property(property="lieuDepart", type="string"),
     *                     @OA\Property(property="lieuArrivee", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function update(LigneRequest $request, Ligne $ligne)
    {
        if ($ligne->etat !== "actif") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Ligne] $ligne->id"
            ], 404);
        }
        $valeurAvant = $ligne->toArray();
        $this->authorize("update", $ligne);
        $lignes_reseau = Ligne::where('reseau_id', $request->user()->reseau_id)->get();
        if (
            $request->validated()['nom'] !== $ligne->nom &&
            $lignes_reseau->contains('nom', $request->validated()['nom'])
        ) {
            return response()->json([
                'message' => 'Desolé cette ligne existe déjà dans votre reseau '
            ], 422);
        }
        $ligne->fill($request->validated());
        $ligne->updated_by = $request->user()->email;
        $ligne->updated_at = now();
        Historique::enregistrerHistorique(
            'lignes',
            $ligne->id,
            auth()->user()->id,
            'update',
            auth()->user()->email,
            auth()->user()->reseau->nom,
            json_encode($valeurAvant),
            json_encode($ligne->toArray())
        );
        $ligne->update();
        return response()->json([
            "message" => "La ligne a bien été mise à jour",
            "ligne" => $ligne
        ], 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/lignes/{ligne}",
     *     summary="Supprimer une ligne",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="204", description="Deleted successfully"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     * @OA\Response(response="404", description="Not Found"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function destroy(Ligne $ligne)
    {
        $valeurAvant = $ligne->toArray();
        $this->authorize("delete", $ligne);
        if ($ligne->etat === "actif") {
            Historique::enregistrerHistorique(
                'lignes',
                $ligne->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($ligne->toArray())
            );
            $ligne->update(['etat' => 'corbeille']);
            event(new LigneUpdated($ligne));
            return response()->json([
                "message" => "La ligne a bien été mise dans la  corbeillee",
                "ligne" => $ligne
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvais mettre dans la corbeille que les lignes actif",
        ], 422);
    }
    /**
     * @OA\PATCH(
     *     path="/api/lignes/delete/{ligne}",
     *     summary="supprimer une ligne de la corbeille",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function delete(Ligne $ligne)
    {
        $valeurAvant = $ligne->toArray();
        $this->authorize("delete", $ligne);
        if ($ligne->etat === "corbeille") {
            Historique::enregistrerHistorique(
                'lignes',
                $ligne->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($ligne->toArray())
            );
            $ligne->update(['etat' => 'supprimé']);
            event(new LigneUpdated($ligne));
            return response()->json([
                "message" => "La ligne a bien été supprimée",
                "ligne" => $ligne
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }

    /**
     * @OA\PATCH(
     *     path="/api/lignes/restaurer/{ligne}",
     *     summary="restaurer une ligne",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function restore(Ligne $ligne)
    {
        $valeurAvant = $ligne->toArray();
        $this->authorize("restore", $ligne);
        if ($ligne->etat === "corbeille") {
            Historique::enregistrerHistorique(
                'lignes',
                $ligne->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($ligne->toArray())
            );
            $ligne->update(['etat' => 'actif']);
            return response()->json([
                "message" => "La ligne a bien été restaurée",
                "ligne" => $ligne
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvais restaurer que les lignes de la corbeille",
        ], 422);
    }

    /**
     * @OA\GET(
     *     path="/api/lignes/deleted/all",
     *     summary="Lister les lignes qui sont dans la corbeille",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function deleted()
    {
        $lignesSupprimees = Cache::rememberForever('lignes_supprimes', function () {
            return Ligne::where('etat', 'corbeille')->get();
        });
        return $lignesSupprimees->isEmpty()
            ? response()->json([
                "message" => "Il n'y a pas de lignes dans la corbeille"
            ], 404)
            :
            response()->json([
                "message" => "La liste des lignes qui se trouve dans la corbeille",
                "lignes" => $lignesSupprimees
            ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/lignes/empty-trash",
     *     summary="vider les lignes qui sont dans la corbeille",
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
     *     tags={"Gestion des lignes"},
     * ),
     */
    public function emptyTrash()
    {
        $lignesSupprimees = Ligne::where('etat', 'corbeille')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();
        if ($lignesSupprimees->all() == null) {
            return response()->json([
                "message" => "Il n'y a pas de lignes dans la corbeille"
            ], 404);
        }
        foreach ($lignesSupprimees as $ligne) {
            $valeurAvant = $ligne->toArray();
            $this->authorize("delete", $ligne);
            Historique::enregistrerHistorique(
                'lignes',
                $ligne->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($ligne->toArray())
            );
            $ligne->update(["etat" => "supprimé"]);
            event(new LigneUpdated($ligne));
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
