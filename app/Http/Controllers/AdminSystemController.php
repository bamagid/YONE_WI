<?php

namespace App\Http\Controllers;

use App\Models\AdminSystem;
use Illuminate\Support\Facades\File;
use App\Http\Requests\UpdateAdminSystemRequest;

class AdminSystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
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

        $adminSystem->fill($request->validated());
        if ($request->file('image')) {
            if (File::exists(storage_path($adminSystem->image))) {
                File::delete(storage_path($adminSystem->image));
            }
            $image = $request->file('image');
            $adminSystem->image = $image->store('images', 'public');;
        }
        $adminSystem->save();
        return response()->json([
            "message" => "information mis a jour avec succés",
            'adminreseau' => $adminSystem
        ], 200);
    }
}
