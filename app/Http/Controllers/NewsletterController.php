<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Http\Requests\NewsletterRequest;
use Illuminate\Support\Facades\Cache;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('subscribe', 'unscribe');
    }
    /**
     * @OA\POST(
     *     path="/api/newsletter/subscribe",
     *     summary="souscrire a la newsletter",
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
     *                     @OA\Property(property="email", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des newsletters"},
     * ),
     */
    public function subscribe(NewsletterRequest $request)
    {
        $email = $request->validated()['email'];
        $newsletter = Newsletter::where('email', $email)->first();
        if (!$newsletter) {
            $subscriber  = Newsletter::create([
                'email' => $email,
            ]);
            Cache::forget('subscribers');
            return response()->json([
                "message" => "L'email a bien été ajouté à la newsletter.",
                "subscriber" => $subscriber
            ], 201);
        } elseif ($newsletter->etat == "desabonné") {
            $newsletter->update(["etat" => "abonné"]);
            Cache::forget('subscribers');
            return response()->json([
                "message" => "L'email a bien été ajouté à la newsletter.",
                "subscriber" => $newsletter
            ], 201);
        }

        return response()->json([
            "message" => "L'email est déjà abonné à la newsletter."
        ], 422);
    }
    /**
     * @OA\POST(
     *     path="/api/newsletter/unscribe",
     *     summary="Se desabonner  a la newsletter",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="email", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des newsletters"},
     * ),
     */
    public function unscribe(NewsletterRequest $request)
    {
        $email = $request->validated()['email'];
        $newsletter = Newsletter::where('email', $email)->first();
        if ($newsletter && $newsletter->etat === "abonné") {
            $newsletter->update(['etat' => 'desabonné']);
            Cache::forget('subscribers');
            return response()->json([
                "message" => "L'email a bien été desabonné à la newsletter.",
                "subscriber" => $newsletter
            ], 200);
        }
        return response()->json([
            "message" => "L'email est n'est pas abonné à la newsletter."
        ], 422);
    }

    /**
     * @OA\GET(
     *     path="/api/newsletter/all",
     *     summary="lister les personnes inscrits sur les newsletter",
     *     description="",
     * security={
     * {"BearerAuth":{} },
     * } ,
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
     *     tags={"Gestion des newsletters"},
     * ),
     */
    public function showSubscribers()
    {
        $subscribers = Cache::rememberForever('subscribers', function () {
            return Newsletter::all();
        });

        return response()->json([
            "message" => "Liste des abonnés à la newsletter",
            "subscribers" => $subscribers
        ], 200);
    }
}
