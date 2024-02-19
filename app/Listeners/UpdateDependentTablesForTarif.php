<?php

namespace App\Listeners;

use App\Events\TarifUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDependentTablesForTarif
{

    /**
     * Handle the event.
     */
    public function handle(TarifUpdated $event): void
    {
        $sections = $event->tarif->sections;
        $sections->each->update(['etat' => $event->tarif->etat]);
    }
}
