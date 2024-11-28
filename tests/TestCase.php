<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests;

use AnthonyEdmonds\LaravelTestingTraits\SignsInUsers;
use Orchestra\Testbench\TestCase as BaseTestCase;
use AnthonyEdmonds\GovukLaravel\Providers\GovukServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use SignsInUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    protected function getPackageProviders($app): array
    {
        return [
            GovukServiceProvider::class,
        ];
    }

    protected function useDatabase(): void
    {
        $this->app->useDatabasePath(__DIR__ . '/Database/Factories');
        $this->runLaravelMigrations();
    }

}
