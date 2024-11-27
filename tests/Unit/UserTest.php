<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit;

use Illuminate\Contracts\View\View;
use NetworkRailBusinessSystems\ActivityLog\ActivityCollection;
use NetworkRailBusinessSystems\ActivityLog\ActivityController;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use Orchestra\Testbench\TestCase;

class UserTest extends TestCase
{
    protected ActivityController $controller;

    protected User $user;

    protected View $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signInWithPermission('manage_users');

        $this->user = User::factory()->create();

        $this->controller = new ActivityController();
        $this->response = $this->controller->user($this->user);
    }

    public function testReturnsView(): void
    {
        $this->assertEquals('activity', $this->response->getData()['content']);
    }

    public function testWithActivities(): void
    {
        $this->assertEquals(
            ActivityCollection::make($this->user->activities)->toArray(request()),
            $this->response->getData()['activities'],
        );
    }

    public function testWithBack(): void
    {
        $this->assertEquals(route('admin.users.show', $this->user), $this->response->getData()['back']);
    }

    public function testWithShowSubject(): void
    {
        $this->assertFalse($this->response->getData()['showSubject']);
    }

    public function testWithSubject(): void
    {
        $this->assertEquals($this->user->id, $this->response->getData()['subject']->id);
    }

    public function testWithTitle(): void
    {
        $this->assertEquals(
            "Activity log of {$this->user->name}",
            $this->response->getData()['title'],
        );
    }
}
