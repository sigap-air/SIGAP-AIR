<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Pengaduan;
use App\Observers\PengaduanObserver;

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
        Pengaduan::observe(PengaduanObserver::class);
    }
}
