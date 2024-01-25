<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Http\Requests\NewsletterRequest;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterRequest $request)
    {
        $email = $request->validated()['email'];

        if (Newsletter::where('email', $email)->exists()) {
            return response()->json([
                "message" => "L'email est déjà abonné à la newsletter."
            ], 422);
        }

        $subscriber = Newsletter::create(['email' => $email]);

        return response()->json([
            "message" => "L'email a bien été ajouté à la newsletter.",
            "subscriber" => $subscriber
        ], 201);
    }

    public function showSubscribers()
    {
        $subscribers = Newsletter::all();

        return response()->json([
            "message" => "Liste des abonnés à la newsletter",
            "subscribers" => $subscribers
        ], 200);
    }
}
