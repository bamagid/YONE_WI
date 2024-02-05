<?php

namespace App\Policies;

use App\Models\Reseau;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReseauPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reseau $reseau)
    {
        return $user->reseau_id === $reseau->id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier les details de ce reseau.');
    }
}
