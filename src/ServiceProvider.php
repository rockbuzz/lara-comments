<?php

namespace Rockbuzz\LaraComments;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public function boot(Filesystem $filesystem)
    {
        $projectPath = database_path('migrations') . '/';
        $localPath = __DIR__ . '/../database/migrations/';

        if (!$this->hasMigrationInProject($projectPath, $filesystem)) {
            $this->loadMigrationsFrom($localPath . '2020_03_05_000000_create_comments_table.php');

            $this->publishes([
                $localPath . '2020_03_05_000000_create_comments_table.php' =>
                    $projectPath . now()->format('Y_m_d_his') . '_create_comments_table.php'
            ], 'migrations');
        }

        $this->publishes([
            __DIR__ . '/../config/comments.php' => config_path('comments.php')
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/comments.php', 'comments');
    }

    private function hasMigrationInProject(string $path, Filesystem $filesystem)
    {
        return count($filesystem->glob($path . '*_create_comments_table.php')) > 0;
    }
}
