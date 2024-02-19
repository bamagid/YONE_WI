<?php

namespace App\Listeners;

use App\Events\ReseauUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;

class UpdateDependentTablesForReseau
{
    /**
     * Handle the event.
     */
    public function handle(ReseauUpdated $event)
    {
        $etat = $event->reseau->etat == "corbeille" ? "bloqué" : $event->reseau->etat;
        $event->reseau->users->each->update([
            'etat' => $etat,
            'motif' => 'Le reseeau que vous devez administrer a été desactivé par l\'administrateur du system'
        ]);
        $event->reseau->lignes->each->update(['etat' => $event->reseau->etat]);
        $event->reseau->abonnements->each->update(['etat' => $event->reseau->etat]);
        $event->reseau->tarifs->each->update(['etat' => $event->reseau->etat]);
        $event->reseau->types->each->update(['etat' => $event->reseau->etat]);
    }
}
