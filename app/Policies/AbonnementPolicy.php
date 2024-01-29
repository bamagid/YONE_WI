<?php

namespace App\Policies;

use App\Models\Abonnement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AbonnementPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Abonnement $abonnement)
    {
        return $user->reseau_id === $abonnement->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à voir ces abonnements.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role_id === 1
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier cet abonnement.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Abonnement $abonnement)
    {
        return $user->reseau_id === $abonnement->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier cet abonnement.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Abonnement $abonnement)
    {
        return $user->reseau_id === $abonnement->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer cet abonnement.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Abonnement $abonnement)
    {
        return $user->reseau_id === $abonnement->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer cet abonnement.');
    }
}
