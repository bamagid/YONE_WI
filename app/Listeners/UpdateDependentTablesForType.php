<?php

namespace App\Listeners;

use App\Events\TypeUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDependentTablesForType
{


    /**
     * Handle the event.
     */
    public function handle(TypeUpdated $event): void
    {
        $lignes = $event->type->lignes;
        $lignes->each->update([ 'etat' => $event->type->etat]);
    }
}
