<?php

namespace AbdelrhmanMohamed2\Crudy;

use Illuminate\Support\ServiceProvider;

class CrudyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/crudy.php', 'crudy');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/crudy.php' => config_path('crudy.php'),
            ], 'crudy-config');
        }
    }

    public function register()
    {
        $this->commands([
            \AbdelrhmanMohamed2\Crudy\Commands\MakeCrud::class,
            \AbdelrhmanMohamed2\Crudy\Commands\MakeModel::class,
            \AbdelrhmanMohamed2\Crudy\Commands\MakeRequests::class,
            \AbdelrhmanMohamed2\Crudy\Commands\MakeMigration::class,
            \AbdelrhmanMohamed2\Crudy\Commands\MakeService::class,
            \AbdelrhmanMohamed2\Crudy\Commands\MakeResource::class,
            \AbdelrhmanMohamed2\Crudy\Commands\MakeLangFiles::class,
            \AbdelrhmanMohamed2\Crudy\Commands\MakeController::class,
            \AbdelrhmanMohamed2\Crudy\Commands\MakeRoute::class,
        ]);

        $this->app->make('AbdelrhmanMohamed2\Crudy\Commands\MakeCrud');
    }
}
