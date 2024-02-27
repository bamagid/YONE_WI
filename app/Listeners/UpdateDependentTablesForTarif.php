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
        $event->tarif->sections->each->update(['etat' => $event->tarif->etat]);
    }
}
