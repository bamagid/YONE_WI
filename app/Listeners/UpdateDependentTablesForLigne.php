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
        $sections = $event->ligne->sections;
        $sections->each->update(["etat" => $event->ligne->etat]);
    }
}
