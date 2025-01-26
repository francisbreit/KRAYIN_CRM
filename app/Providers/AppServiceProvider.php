<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;

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

        // Configurar timezone global da aplicação
        config(['app.timezone' => 'America/Sao_Paulo']);
        date_default_timezone_set('America/Sao_Paulo');

        // Configurar o formato padrão para datas e horas no Carbon
        Carbon::setLocale(config('app.locale', 'pt_BR'));
        Carbon::macro('toFormattedDateTime', function () {
            return $this->format('d-m-Y H:i:s');
        });
    }
}
