<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{

    public function searching(Request $request)
    {

        $searchTerm = $request->query('q');

        // Recherche des reseaux par nom
        $reseaux = DB::table('reseaus')
            ->select('id', 'nom', 'etat')
            ->where('nom', 'LIKE', "%$searchTerm%")
            ->get();

        // Recherche des roles par nom
        $roles = DB::table('roles')
            ->select('id', 'nom')
            ->where('nom', 'LIKE', "%$searchTerm%")
            ->get();

        // Recherche des utilisateurs par nom
        $users = DB::table('users')
            ->select(['*'])
            ->where('nom', 'LIKE', "%$searchTerm%")
            ->get();

        if ($reseaux && $roles && $users) {
            return response()->json([
                'reseaux' => $reseaux,
                'roles' => $roles,
                'users' => $users,
            ]);
        }
        return response()->json([
            'error' => "Aucun résultat trouvé pour la recherche : '$searchTerm'",
        ], 404);
    }
}
