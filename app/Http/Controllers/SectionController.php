<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Http\Requests\SectionRequest;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index', 'show');
    }


    public function index()
    {
        $sections = Section::where('etat', 'actif')->get();
        return response()->json([
            "message" => "La liste des sections actives",
            "sections" => $sections
        ], 200);
    }


    public function show(Section $section)
    {
        if ($section->etat == "supprimé") {
            return response()->json([
                "message" => "No query results for model [App\\Models\\Section] $section->id"
            ], 404);
        }
        return response()->json([
            "message" => "Voici la section que vous recherchez",
            "section" => $section
        ], 200);
    }

    public function store(SectionRequest $request)
    {
        $section = Section::create($request->validated());
        return response()->json([
            "message" => "La section a bien été enregistrée",
            "section" => $section
        ], 201);
    }

    public function update(SectionRequest $request, Section $section)
    {
        $section->update($request->validated());
        return response()->json([
            "message" => "La section a bien été mise à jour",
            "section" => $section
        ], 200);
    }

    public function destroy(Section $section)
    {
        $section->update(['etat' => 'corbeille']);
        return response()->json([
            "message" => "La section a bien été mis dans la corbeille",
            "section" => $section
        ]);
    }


    public function restore(Section $section)
    {
        $section->update(['etat' => 'actif']);
        return response()->json([
            "message" => "La section a bien été restaurée",
            "section" => $section
        ]);
    }


    public function deleted()
    {
        $sectionsSupprimees = Section::where('etat', 'corbeille')->get();
        return response()->json([
            "message" => "La liste des sections qui sont misent dans la corbeille",
            "sections" => $sectionsSupprimees
        ], 200);
    }

    public function emptyTrash()
    {
        dd('pas vraie');
        $sectionsSupprimes = Section::where('etat', 'corbeille')->get();
        foreach ($sectionsSupprimes as $section) {
            $section->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
