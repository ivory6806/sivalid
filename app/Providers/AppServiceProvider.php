<?php

namespace App\Providers;

use App\Models\Sertifikat;
use App\Models\Surat;
use App\Observers\SertifikatObserver;
use App\Observers\SuratObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // <-- TAMBAHKAN INI

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
        // TAMBAHKAN BARIS INI:
        Schema::defaultStringLength(191);

        Surat::observe(SuratObserver::class);
        Sertifikat::observe(SertifikatObserver::class);

        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('http');
            // \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}