<?php

namespace App\Listeners;

use App\Events\LigneUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDependentTablesForLigne
{


    /**
     * Handle the event.
     */
    public function handle(LigneUpdated $event): void
    {
        $event->ligne->sections->each->update(["etat" => $event->ligne->etat]);
    }
}
