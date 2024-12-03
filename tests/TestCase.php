<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests;

use AnthonyEdmonds\LaravelTestingTraits\SignsInUsers;
use NetworkRailBusinessSystems\ActivityLog\Activity;
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
        config()->set('activitylog.activity_model', Activity::class);
        config()->set('activitylog.default_auth_driver', null);
        config()->set('activitylog.default_log_name', 'default');
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
