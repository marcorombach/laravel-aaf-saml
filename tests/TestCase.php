<?php

namespace Marcorombach\LaravelAafSAML\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Marcorombach\LaravelAafSAML\LaravelAafSAMLServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Marcorombach\\LaravelAafSAML\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelAafSAMLServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-aaf-saml_table.php.stub';
        $migration->up();
        */
    }
}
