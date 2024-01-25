<?php

namespace App\Http\Controllers;

// ContactController.php

use App\Http\Requests\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $messages = Contact::all();
        return response()->json([
            'message' => 'voici la liste des messages',
            'contact' => $messages,
        ], 201);
    }

    public function store(ContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        return response()->json([
            'message' => 'Votre message a bien été envoyé. Nous vous contacterons bientôt.',
            'contact' => $contact,
        ], 201);
    }
}
