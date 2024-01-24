<?php

namespace App\Http\Controllers;

// Importez les classes nécessaires
use App\Models\Section;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index', 'show');
    }
    // Méthode pour afficher toutes les sections
    public function index()
    {
        $sections = Section::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des sections actives",
            "sections" => $sections
        ], 200);
    }

    // Méthode pour afficher une section spécifique
    public function show(Section $section)
    {
        return response()->json([
            "message" => "Voici la section que vous recherchez",
            "section" => $section
        ], 200);
    }

    // Méthode pour créer une nouvelle section
    public function store(StoreSectionRequest $request)
    {
        $section = Section::create($request->validated());
        return response()->json([
            "message" => "La section a bien été enregistrée",
            "section" => $section
        ], 201);
    }

    // Méthode pour mettre à jour une section existante
    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->validated());
        return response()->json([
            "message" => "La section a bien été mise à jour",
            "section" => $section
        ], 200);
    }

    // Méthode pour supprimer une section (marquer comme "supprimée")
    public function destroy(Section $section)
    {
        $section->update(['etat' => 'supprimé']);
        return response()->json([
            "message" => "La section a bien été supprimée",
            "section" => $section
        ]);
    }

    // Méthode pour restaurer une section supprimée
    public function restore(Section $section)
    {
        $section->update(['etat' => 'actif']);
        return response()->json([
            "message" => "La section a bien été restaurée",
            "section" => $section
        ]);
    }
}
