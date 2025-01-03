<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Policies;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use NetworkRailBusinessSystems\ActivityLog\ActivityController;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\TestCase;

class UserPolicyTest extends TestCase
{
    protected UserPolicy $policy;

    protected User $user;

    protected ActivityController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new UserPolicy();

        $this->user = User::factory()->create();
        $this->user = $this->signIn($this->user);

        $this->controller = new ActivityController();
    }

    public function testDenies(): void
    {
        $this->assertPolicyDenies(
            $this->policy->manage($this->user),
            'User cannot manage',
        );
    }

    public function testExceptionWithUnauthorised(): void
    {
        $this->expectException(AuthorizationException::class);
        $this->controller->authorize('manage', $this->user);
    }

    public function testAllowsWithPermission(): void
    {
        $user = $this->user;

        Gate::define('manage', function ($user) {
            return true;
        });

        $this->assertPolicyAllows(
            $this->policy->manage($this->user),
            'User can manage',
        );
    }
}
