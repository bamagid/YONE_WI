<?php

namespace App\Policies;

use App\Models\Ligne;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LignePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ligne $ligne)
    {
        return $user->reseau_id === $ligne->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à voir ces lignes.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role_id === 1
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier cette ligne.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ligne $ligne)
    {
        return $user->reseau_id === $ligne->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier cette ligne.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ligne $ligne)
    {
        return $user->reseau_id === $ligne->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer cette ligne.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ligne $ligne)
    {
        return $user->reseau_id === $ligne->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer cette ligne.');
    }
}
