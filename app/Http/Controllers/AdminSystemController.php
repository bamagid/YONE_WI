<?php

namespace App\Http\Controllers;

use App\Models\AdminSystem;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\StoreAdminSystemRequest;
use App\Http\Requests\UpdateAdminSystemRequest;
use Illuminate\Support\Facades\Hash;

class AdminSystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function update(UpdateAdminSystemRequest $request)
    {
        $adminSystem = auth()->guard('admin')->user();
        $adminSystem->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            "message" => "information mis a jour avec succés",
            'adminreseau' => $adminSystem
        ], 200);
    }
}
