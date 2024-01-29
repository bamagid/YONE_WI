<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Http\Requests\SectionRequest;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
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
        $this->authorize('create', Section::class);
        $section = Section::create($request->validated());
        return response()->json([
            "message" => "La section a bien été enregistrée",
            "section" => $section
        ], 201);
    }

    public function update(SectionRequest $request, Section $section)
    {
        $this->authorize("update", $section);
        $section->update($request->validated());
        return response()->json([
            "message" => "La section a bien été mise à jour",
            "section" => $section
        ], 200);
    }

    public function destroy(Section $section)
    {
        $this->authorize("delete", $section);
        if ($section->etat === "actif") {
            $section->update(['etat' => 'corbeille']);
            return response()->json([
                "message" => "La section a bien été mis dans la corbeille",
                "section" => $section
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "Desole vous ne pouvais mettre dans la corbeille que les sections actif",
        ], 422);
    }
    public function delete(Section $section)
    {
        $this->authorize("delete", $section);
        if ($section->etat === "corbeille") {
            $section->update(['etat' => 'supprimé']);
            return response()->json([
                "message" => "La section a bien été supprimé",
                "section" => $section
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvez pas supprimé un element qui n'est pas dans la corbeille",
        ], 422);
    }

    public function restore(Section $section)
    {
        $this->authorize("update", $section);
        if ($section->etat === "corbeille") {
            $section->update(['etat' => 'actif']);
            return response()->json([
                "message" => "La section a bien été restaurée",
                "section" => $section
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "Vous ne pouvais restaurer que les sections de la corbeille",
        ], 422);
    }


    public function deleted()
    {
        $sectionsSupprimees = Section::where('etat', 'corbeille')->get();
        $this->authorize("view", $sectionsSupprimees);
        if (empty($sectionsSupprimees)) {
            return response()->json([
                "error" => "Il n'y a pas de sections supprimées"
            ], 404);
        }
        return response()->json([
            "message" => "La liste des sections qui sont misent dans la corbeille",
            "sections" => $sectionsSupprimees
        ], 200);
    }

    public function emptyTrash()
    {
        $sectionsSupprimees = Section::where('etat', 'corbeille')->get();
        if (empty($sectionsSupprimees)) {
            return response()->json([
                "error" => "Il n'y a pas de sections supprimées"
            ], 404);
        }
        foreach ($sectionsSupprimees as $section) {
            $this->authorize('delete', $section);
            $section->update(["etat" => "supprimé"]);
        }
        return response()->json([
            "message" => "La corbeille a été vidée avec succès"
        ], 200);
    }
}
