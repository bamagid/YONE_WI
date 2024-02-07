<?php

namespace App\Policies;

use App\Models\AdminSystem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(AdminSystem $adminSystem)
    {
        return $adminSystem
        ? Response::allow()
        : Response::deny('Vous n\'êtes pas autorisé à voir la liste des utilisateurs.');
    }
    public function create(AdminSystem $adminSystem)
    {
        return $adminSystem
        ? Response::allow()
        : Response::deny('Vous n\'êtes pas autorisé à creer un utilisateur.');
    }

    public function update(AdminSystem $adminSystem, User $model)
    {
        return $adminSystem
        ? Response::allow()
        : Response::deny('Vous n\'êtes pas autorisé à bloquer un utilisateur.');
    }
    public function delete(AdminSystem $adminSystem, User $model)
    {
        return $adminSystem
        ? Response::allow()
        : Response::deny('Vous n\'êtes pas autorisé à supprimer un utilisateur.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(AdminSystem $adminSystem, User $model)
    {
        return $adminSystem
        ? Response::allow()
        : Response::deny('Vous n\'êtes pas autorisé à debloquer un utilisateur.');
    }
}
