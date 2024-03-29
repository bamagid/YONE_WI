<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Models\Historique;
use App\Events\TarifUpdated;
use App\Http\Requests\TarifRequest;
use Illuminate\Support\Facades\Cache;

class TarifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }

    /**
     * @OA\GET(
     *     path="/api/tarifs",
     *     summary="Lister les tarifs",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des tarifs"},
     * ),
     */
    public function index()
    {
        $tarifs = Cache::rememberForever('tarifs_actifs', function () {
            return Tarif::where('etat', 'actif')->get();
        });
        return response()->json([
            "message" => "La liste des tarifs actifs",
            "tarifs" => $tarifs
        ], 200);
    }

    /**
     * @OA\GET(
     *     path="/api/mestarifs",
     *     summary="Lister mes tarifs",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des tarifs"},
     * ),
     */
    public function mestarifs()
    {
        $tarifs = Cache::rememberForever('mes_tarifs_actifs', function () {
            return Tarif::where('etat', 'actif')
                ->where('reseau_id', auth()->user()->reseau_id)
                ->get();
        });
        return $tarifs->isEmpty() ?
            response()->json([
                "message" => "Vous n'avez pas de tarifs actifs"
            ])
            :
            response()->json([
                "message" => "La liste de mes tarifs actifs",
                "tarifs" => $tarifs
            ], 200);
    }


    /**
     * @OA\GET(
     *     path="/api/tarifs/{tarif}",
     *     summary="Afficher un tarif",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="tarif", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des tarifs"},
     * ),
     */
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

    /**
     * @OA\POST(
     *     path="/api/tarifs",
     *     summary="Ajouter un tarif",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
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
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des tarifs"},
     * ),
     */

    public function store(TarifRequest $request)
    {
        $this->authorize('create', Tarif::class);
        $tarif = new Tarif();
        $tarif->fill($request->validated());
        $tarif->reseau_id = $request->user()->reseau_id;
        $tarif->created_by = $request->user()->email;
        $tarif->created_at = now();
        $tarif->save();
        Historique::enregistrerHistorique(
            'tarifs',
            $tarif->id,
            auth()->user()->id,
            'create',
            auth()->user()->email,
            auth()->user()->reseau->nom,
            null,
            json_encode($tarif->toArray())
        );
        return response()->json([
            "message" => "Le tarif a bien été enregistré",
            "tarif" => $tarif
        ], 201);
    }

    /**
     * @OA\PATCH(
     *     path="/api/tarifs/{tarif}",
     *     summary="Modifier un tarif",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="tarif", required=false, @OA\Schema(type="string"),
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
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des tarifs"},
     * ),
     */

    public function update(TarifRequest $request, Tarif $tarif)
    {
        if ($tarif->etat !== "actif") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Tarif] $tarif->id"
            ], 404);
        }
        $valeurAvant = $tarif->toArray();
        $this->authorize("update", $tarif);
        $tarif->fill($request->validated());
        $tarif->updated_by = $request->user()->email;
        $tarif->updated_at = now();
        Historique::enregistrerHistorique(
            'tarifs',
            $tarif->id,
            auth()->user()->id,
            'update',
            auth()->user()->email,
            auth()->user()->reseau->nom,
            json_encode($valeurAvant),
            json_encode($tarif->toArray())
        );
        $tarif->update();
        return response()->json([
            "message" => "Le tarif a bien été mis à jour",
            "tarif" => $tarif
        ], 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/tarifs/{tarif}",
     *     summary="Supprimer un tarif",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="204", description="Deleted successfully"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     * @OA\Response(response="404", description="Not Found"),
     *     @OA\Parameter(in="path", name="tarif", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des tarifs"},
     * ),
     */
    public function destroy(Tarif $tarif)
    {
        $valeurAvant = $tarif->toArray();
        $this->authorize("delete", $tarif);
        if ($tarif->etat === "actif") {
            Historique::enregistrerHistorique(
                'tarifs',
                $tarif->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($tarif->toArray())
            );
            $tarif->update(['etat' => 'corbeille']);
            event(new TarifUpdated($tarif));
            return response()->json([
                "message" => "Le tarif a bien été mis dans la corbeille",
                "tarif" => $tarif
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvez mettre dans la corbeille que les tarifs actif",
        ], 422);
    }
    /**
     * @OA\PATCH(
     *     path="/api/tarifs/restaurer/{tarif}",
     *     summary="restaurer un tarif",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="tarif", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des tarifs"},
     * ),
     */

    public function restore(Tarif $tarif)
    {
        $this->authorize("restore", $tarif);
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
    /**
     * @OA\PATCH(
     *     path="/api/tarifs/delete/{tarif}",
     *     summary="supprimer un tarif de la corbeille",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="path", name="tarif", required=false, @OA\Schema(type="string"),
     * ),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des tarifs"},
     * ),
     */
    public function delete(Tarif $tarif)
    {
        $valeurAvant = $tarif->toArray();
        $this->authorize("delete", $tarif);
        if ($tarif->etat === "corbeille") {
            $tarif->update(['etat' => 'supprimé']);
            Historique::enregistrerHistorique(
                'tarifs',
                $tarif->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($tarif->toArray())
            );
            event(new TarifUpdated($tarif));
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

    /**
     * @OA\GET(
     *     path="/api/tarifs/deleted/all",
     *     summary="Lister les tarifs qui sont dans la corbeille",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des tarifs"},
     * ),
     */
    public function deleted()
    {
        $tarifsSupprimes = Cache::rememberForever('tarifs_supprimes', function () {
            return Tarif::where('etat', 'corbeille')
                ->where('reseau_id', auth()->user()->reseau_id)
                ->get();
        });
        return  $tarifsSupprimes->isEmpty() ?
            response()->json([
                "message" => "Il n'y a pas de tarifs dans la corbielle"
            ], 404)
            :
            response()->json([
                "message" => "La liste des tarifs qui sont dans la corbeille",
                "tarifs" => $tarifsSupprimes
            ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/tarifs/empty-trash",
     *     summary="vider les tarifs qui sont dans la corbeille",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des tarifs"},
     * ),
     */
    public function emptyTrash()
    {
        $tarifsSupprimes = Tarif::where('etat', 'corbeille')
            ->where('reseau_id', auth()->user()->reseau_id)
            ->get();
        if ($tarifsSupprimes->all() == null) {
            return response()->json([
                "message" => "Il n'y a pas de tarifs dans la corbielle"
            ], 404);
        }
        foreach ($tarifsSupprimes as $tarif) {
            $valeurAvant = $tarif->toArray();
            Historique::enregistrerHistorique(
                'tarifs',
                $tarif->id,
                auth()->user()->id,
                'update',
                auth()->user()->email,
                auth()->user()->reseau->nom,
                json_encode($valeurAvant),
                json_encode($tarif->toArray())
            );
            $tarif->update(["etat" => "supprimé"]);
            event(new TarifUpdated($tarif));
        }
        return response()->json([
            "message" => "La corbeille des tarifs a été vidée avec succès"
        ], 200);
    }
}
