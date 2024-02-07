<?php

namespace App\Http\Controllers;

use App\Models\Historique;
use Illuminate\Http\Request;

class HistoriqueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * @OA\GET(
     *     path="/api/historiques",
     *     summary="Lister l'historique",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des historiques"},
     * ),
     */
    public  function index()
    {
        $historiques = Historique::all();
        return response()->json([
            'status' => true,
            "message"=>"voici  l'historique de la plateforme",
            "historiques" => $historiques
        ]);
    }

    /**
     * @OA\POST(
     *     path="/api/historiques/classe",
     *     summary="afficher l'historique d'une classe",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="Created successfully"),
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
     *                     @OA\Property(property="entite", type="string")
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des historiques"},
     * ),
     */

    public  function historiquesentite(Request $request)
    {
        $historiques = Historique::where("Entite", $request->entite)->get();
        if ($historiques->all() == null) {
            return response()->json([
                'status' => false,
                'message' => 'Aucun historique trouvé pour cette classe'], 404);
        }
        return response()->json([
            'status' => true,
            'message'=> 'voici l\'historiques des '. $request->entite,
            "historiques" => $historiques
        ]);
    }

    /**
     * @OA\POST(
     *     path="/api/historiques/user",
     *     summary="afficher l'historique d'un admin reseau",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="Created successfully"),
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
     *                     @OA\Property(property="id_user", type="string")
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des historiques"},
     * ),
     */
    public  function historiquesuser(Request $request)
    {
        $historiques = Historique::where("id_user", $request->id_user)->get();
        if ($historiques->all() == null) {
         return response()->json([
        'status' => false,
        'message' => 'Aucun historique trouvé pour cet utilisateur'], 404);
        }
        return response()->json([
            'status' => true,
            'message'=> 'Voici l\historique de l\'utilisateur avec l\'id '. $request->id_user,
            "historiques" => $historiques
        ]);
    }
}
