<?php

namespace Rockbuzz\LaraComments;

use Illuminate\Support\ServiceProvider;

class CommentsServiceProvider extends ServiceProvider
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
