<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(\App\Blueprint\Repositories\PrototypeRepository::class, \App\Repositories\PrototypeRepositoryEloquent::class);
        $this->app->bind('prototype', \App\Repositories\PrototypeRepositoryEloquent::class);
        //:end-bindings:
    }
}
