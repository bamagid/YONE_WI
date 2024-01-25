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
        $adminSystem = AdminSystem::FindOrFail(1);
        $adminSystem->fill($request->validated());
        $adminSystem->password = Hash::make($request->get('password'));
        $adminSystem->save();
        return response()->json([
            "message" => "information mis a jour avec succés",
            'adminreseau' => $adminSystem
        ], 200);
    }
}
