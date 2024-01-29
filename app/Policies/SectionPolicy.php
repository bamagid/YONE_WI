<?php

namespace App\Policies;

use App\Models\Section;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SectionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Section $section)
    {
        return $user->reseau_id === $section->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à voir ces sections.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role_id === 1
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier cette section.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Section $section)
    {
        return $user->reseau_id === $section->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier cette section.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Section $section)
    {
        return $user->reseau_id === $section->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer cette section.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Section $section)
    {
        return $user->reseau_id === $section->reseau_id
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer cette section.');
    }
}
