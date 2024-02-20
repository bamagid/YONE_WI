<?php

namespace App\Http\Controllers;

use App\Models\AdminSystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateAdminSystemRequest;

class AdminSystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->only('update');
    }

    /**
     * @OA\POST(
     *     path="/api/updateadmin",
     *     summary="Modification de compte admin",
     *     description="",
     *security={
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
     *                     @OA\Property(property="prenom", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="telephone", type="integer"),
     *                     @OA\Property(property="old_password", type="string"),
     *                     @OA\Property(property="password", type="string"),
     *                     @OA\Property(property="password_confirmation", type="string"),
     *                     @OA\Property(property="image", type="string", format="binary"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de compte"},
     * )
     */

    public function update(UpdateAdminSystemRequest $request)
    {
        $adminSystem = AdminSystem::FindOrFail(1);
        if ($request->old_password && Hash::check($request->old_password, $adminSystem->password) === false) {
            return response()->json([
                "error" => "Ancien mot de passe incorrect"
            ], 422);
        }
        $adminSystem->fill($request->validated());
        if ($request->file('image')) {
            if (File::exists(storage_path($adminSystem->image))) {
                File::delete(storage_path($adminSystem->image));
            }
            $image = $request->file('image');
            $adminSystem->image = $image->store('images', 'public');;
        }
        $adminSystem->update();
        return response()->json([
            "message" => "information mis a jour avec succés",
            'adminreseau' => $adminSystem
        ], 200);
    }

      /**
     * @OA\GET(
     *     path="/api/contactsadmin",
     *     summary="Afficher les contacts de l'administrateur system",
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
     * ),
     */
    public function showContact()
    {
        $adminSystem = AdminSystem::FindOrFail(1);
        return response()->json([
            'email' => $adminSystem->email,
            'telephone' => $adminSystem->telephone,
        ], 200);
    }
}
