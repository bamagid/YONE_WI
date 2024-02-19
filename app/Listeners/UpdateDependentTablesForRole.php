<?php

namespace App\Listeners;

use App\Events\RoleUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDependentTablesForRole
{

    /**
     * Handle the event.
     */
    public function handle(RoleUpdated $event): void
    {
        $users = $event->role->users;
        $etat = $event->role->etat == "corbeile" ? "bloqué" : $event->role->etat;
        $users->each->update([
            'etat' => $etat,
            'motif' => 'Le role qui vous aviez a été desactivé par l\'administrateur du system'
        ]);
    }
}
