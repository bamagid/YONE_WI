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
