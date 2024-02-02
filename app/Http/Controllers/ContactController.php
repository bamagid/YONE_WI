<?php

namespace App\Http\Controllers;

// ContactController.php

use App\Http\Requests\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('store');
    }
    /**
     * @OA\GET(
     *     path="/api/contacts",
     *     summary="Lister les contacts",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="200", description="OK"),
     * @OA\Response(response="404", description="Not Found"),
     * @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string"),
     * ),
     *     tags={"Gestion des contacts"},
     * )
     */
    public function index()
    {
        $messages = Contact::all();
        return response()->json([
            'message' => 'voici la liste des messages',
            'contact' => $messages,
        ], 200);
    }

    /**
     * @OA\POST(
     *     path="/api/contacts",
     *     summary="Ajouter un contact",
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
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="sujet", type="string"),
     *                     @OA\Property(property="contenu", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des contacts"},
     * )
     */
    public function store(ContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        return response()->json([
            'message' => 'Votre message a bien été envoyé. Nous vous contacterons bientôt.',
            'contact' => $contact,
        ], 201);
    }
}
