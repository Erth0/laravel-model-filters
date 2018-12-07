<?php

namespace Mukja\LaravelFilters;

use Illuminate\Support\ServiceProvider;
use Mukja\LaravelFilters\Commands\CreateNewFilterCommand;
use Mukja\LaravelFilters\Commands\CreateNewModelFilterCommand;

class LaravelFiltersServiceProvider extends ServiceProvider
{


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateNewModelFilterCommand::class,
                CreateNewFilterCommand::class
            ]);
        }
    }
}
