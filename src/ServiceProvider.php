<?php

namespace Rockbuzz\LaraComments;

use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database');

        $this->publishes([
            __DIR__ . '/database/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/config/comments.php' => config_path('comments.php')
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/comments.php', 'comments');
    }
}
