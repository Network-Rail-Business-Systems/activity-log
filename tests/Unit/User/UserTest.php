<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit\User;

use Illuminate\Contracts\View\View;
use NetworkRailBusinessSystems\ActivityLog\ActivityCollection;
use NetworkRailBusinessSystems\ActivityLog\ActivityController;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\TestCase;

class UserTest extends TestCase
{
    protected ActivityController $controller;

    protected User $user;

    protected View $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();

        $this->user = User::factory()->create();

        $this->controller = new ActivityController();
        $this->response = $this->user->viewActions();
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
        $this->assertTrue($this->response->getData()['showSubject']);
    }

    public function testWithSubject(): void
    {
        $this->assertEquals($this->user->id, $this->response->getData()['subject']->id);
    }

    public function testWithTitle(): void
    {
        $this->assertEquals(
            "Activities performed by {$this->user->name}",
            $this->response->getData()['title'],
        );
    }
}
