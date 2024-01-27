<?php

namespace App\Http\Controllers;

use App\Models\AdminSystem;
use App\Http\Requests\UpdateAdminSystemRequest;

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
        $adminSystem->save();
        return response()->json([
            "message" => "information mis a jour avec succés",
            'adminreseau' => $adminSystem
        ], 200);
    }
}
