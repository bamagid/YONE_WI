<?php

namespace App\Policies;

use App\Models\Tarif;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TarifPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role_id === 1
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à creer de tarif.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tarif $tarif)
    {
        return $user->reseau_id === $tarif->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier ce tarif.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tarif $tarif)
    {
        return $user->reseau_id === $tarif->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer ce tarif.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tarif $tarif)
    {
        return $user->reseau_id === $tarif->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à restaurer ce tarif.');
    }
}
