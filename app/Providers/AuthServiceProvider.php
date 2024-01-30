<?php

namespace App\Providers;

use App\Models\Abonnement;
use App\Models\Ligne;
use App\Models\Reseau;
use App\Models\Section;
use App\Models\Tarif;
use App\Models\Type;
use App\Policies\AbonnementPolicy;
use App\Policies\LignePolicy;
use App\Policies\ReseauPolicy;
use App\Policies\SectionPolicy;
use App\Policies\TarifPolicy;
use App\Policies\TypePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Abonnement::class => AbonnementPolicy::class,
        Ligne::class => LignePolicy::class,
        Section::class => SectionPolicy::class,
        Tarif::class => TarifPolicy::class,
        Type::class => TypePolicy::class,
        Reseau::class => ReseauPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
