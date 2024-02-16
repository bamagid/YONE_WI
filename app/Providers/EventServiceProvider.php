<?php

namespace App\Providers;

use App\Events\LigneUpdated;
use App\Events\ReseauUpdated;
use App\Events\RoleUpdated;
use App\Events\TarifUpdated;
use App\Events\TypeUpdated;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\UpdateDependentTablesForReseau;
use App\Listeners\UpdateDependentTablesForLigne;
use App\Listeners\UpdateDependentTablesForRole;
use App\Listeners\UpdateDependentTablesForTarif;
use App\Listeners\UpdateDependentTablesForType;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LigneUpdated::class => [
            UpdateDependentTablesForLigne::class,
        ],
        ReseauUpdated::class => [
            UpdateDependentTablesForReseau::class,
        ],
        RoleUpdated::class => [
            UpdateDependentTablesForRole::class
        ],
        TarifUpdated::class => [
            UpdateDependentTablesForTarif::class
        ],
        TypeUpdated::class => [
            UpdateDependentTablesForType::class
        ],

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
