<?php

namespace App\Http\Controllers;

// ContactController.php

use App\Http\Requests\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{

    // Affiche la liste des messages de contact pour l'administrateur
    public function index()
    {
        $messages = Contact::all();
        return response()->json([
            'message' => 'voici la liste des messages',
            'contact' => $messages,
        ], 201);
    }
    // Traite le formulaire de contact
    public function store(ContactRequest $request)
    {

        // Crée un nouvel enregistrement dans la table contacts
        $contact = Contact::create($request->validated());

        return response()->json([
            'message' => 'Votre message a bien été envoyé. Nous vous contacterons bientôt.',
            'contact' => $contact,
        ], 201);
    }
}
