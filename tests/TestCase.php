<?php

namespace Tests;

use Tests\Models\User;
use Illuminate\Support\Facades\Config;
use Rockbuzz\LaraComments\ServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testing']);

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__ . '/../src/database/migrations'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__ . '/database/migrations'),
        ]);

        $this->withFactories(__DIR__ . '/database/factories');
        $this->withFactories(__DIR__.'/../src/database/factories');

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

    protected function create(string $class, array $attributes = [], int $times = null)
    {
        return factory($class, $times)->create($attributes);
    }

    protected function make(string $class, array $attributes = [], int $times = null)
    {
        return factory($class, $times)->make($attributes);
    }
}
