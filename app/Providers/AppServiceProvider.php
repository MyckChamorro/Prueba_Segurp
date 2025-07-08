<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Nota;
use App\Models\Asignatura;
use App\Observers\NotaObserver;
use App\Policies\NotaPolicy;
use App\Policies\AsignaturaPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Nota::observe(NotaObserver::class);
        
        // Registrar políticas manualmente
        Gate::policy(Nota::class, NotaPolicy::class);
        Gate::policy(Asignatura::class, AsignaturaPolicy::class);
    }
}
