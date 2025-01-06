<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit\Provider;

use Illuminate\Support\Facades\Route;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\TestCase;

class ProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testActioner(): void
    {
        Route::activityLogActioner(User::class);
        $routes = Route::getRoutes();

        $this->assertNotNull($routes->getByName('actions'));
    }

    public function testActioned(): void
    {
        Route::activityLogActioned(User::class);
        $routes = Route::getRoutes();

        $this->assertNotNull($routes->getByName('activities'));
    }
}
