<?php

namespace App\Policies;

use App\Models\Type;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TypePolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role_id === 1
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier ce type.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Type $type)
    {
        return $user->reseau_id === $type->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier ce type.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Type $type)
    {
        return $user->reseau_id === $type->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer ce type.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Type $type)
    {
        return $user->reseau_id === $type->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer ce type.');
    }
}
