<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Forçar HTTPS em todas as URLs geradas pela aplicação
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        // Configurar comprimento padrão para colunas string
        Schema::defaultStringLength(191);
    }
}
