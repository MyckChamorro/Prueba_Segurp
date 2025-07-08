<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Nota;
use App\Observers\NotaObserver;

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
    }
}
