<?php

namespace Rockbuzz\LaraComments\Tests;

use Rockbuzz\LaraComments\Tests\Models\User;
use Illuminate\Support\Facades\Config;
use Rockbuzz\LaraComments\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testing']);

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__ . '/../database/migrations'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__ . '/database/migrations'),
        ]);

        $baseFactoryPath = 'Rockbuzz\\LaraComments\\Tests\\Database\\Factories\\';

        Factory::guessFactoryNamesUsing(
            static fn(string $modelName) => $baseFactoryPath . class_basename($modelName) . 'Factory'
        );

        Config::set('comments.models.commenter', User::class);
    }


    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }


    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function signIn($attributes = [], $user = null)
    {
        $this->actingAs($user ?: $this->create(User::class, $attributes));
        return $this;
    }
}