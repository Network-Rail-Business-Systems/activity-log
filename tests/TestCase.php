<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests;

use AnthonyEdmonds\LaravelTestingTraits\SignsInUsers;
use NetworkRailBusinessSystems\ActivityLog\ActivityLogServiceProvider;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use SignsInUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useDatabase();
        $this->withoutVite();

        config()->set('testing-traits.user_model', User::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ActivityLogServiceProvider::class,
        ];
    }

    protected function useDatabase(): void
    {
        $this->app->useDatabasePath(__DIR__ . '/Database');
        $this->runLaravelMigrations();
    }
}
