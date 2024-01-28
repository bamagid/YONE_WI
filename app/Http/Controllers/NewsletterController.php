<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Http\Requests\NewsletterRequest;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('subscribe');
    }
    public function subscribe(NewsletterRequest $request)
    {
        $email = $request->validated()['email'];

        if (Newsletter::where('email', $email)->exists()) {
            return response()->json([
                "message" => "L'email est déjà abonné à la newsletter."
            ], 422);
        }

        $subscriber = Newsletter::create($request->validated());
        return response()->json([
            "message" => "L'email a bien été ajouté à la newsletter.",
            "subscriber" => $subscriber
        ], 201);
    }
    public function unscribe(NewsletterRequest $request)
    {
        $email = $request->validated()['email'];

        if (Newsletter::where('email', $email)->exists()) {
            $subscriber = Newsletter::update(['etat' => 'desabonner']);
            return response()->json([
                "message" => "L'email a bien été desabonné à la newsletter.",
                "subscriber" => $subscriber
            ], 201);
        }
        return response()->json([
            "message" => "L'email est n'est pas abonné à la newsletter."
        ], 422);
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
