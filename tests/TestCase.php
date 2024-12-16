<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests;

use AnthonyEdmonds\LaravelTestingTraits\SignsInUsers;
use Illuminate\Support\Facades\Gate;
use NetworkRailBusinessSystems\ActivityLog\Activity;
use NetworkRailBusinessSystems\ActivityLog\ActivityLogServiceProvider;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\Policies\UserPolicy;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use SignsInUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useDatabase();
        $this->withoutVite();
        $this->registerPolicies();
        $this->setUpRoutes();

        config()->set('testing-traits.user_model', User::class);
        config()->set('activitylog.activity_model', Activity::class);
        config()->set('activitylog.default_auth_driver', null);
        config()->set('activitylog.default_log_name', 'default');
        config()->set('activitylog.table_name', 'activity_log');
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

    protected function registerPolicies(): void
    {
        Gate::policy(User::class, UserPolicy::class);
    }

    protected function setUpRoutes(): void
    {
        $router = app('router');

        $router
            ->name('admin.users.show')
            ->get('/admin/users/{user}');
    }
}
