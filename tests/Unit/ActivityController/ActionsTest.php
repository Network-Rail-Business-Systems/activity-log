<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit\ActivityController;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use NetworkRailBusinessSystems\ActivityLog\ActivityCollection;
use NetworkRailBusinessSystems\ActivityLog\ActivityController;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\AuthorisedUser;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\TestCase;

class ActionsTest extends TestCase
{
    protected ActivityController $controller;

    protected User $user;

    protected View $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->signIn();
        activity()
            ->by($this->user)
            ->log('Toot');

        $this->controller = new ActivityController();
        $this->response = $this->controller->actions($this->user->id, User::class);
    }

    public function testReturnsView(): void
    {
        $this->assertEquals('govuk-activity-log::activity', $this->response->getData()['content']);
    }

    public function testWithActivities(): void
    {
        $this->assertEquals(
            ActivityCollection::make($this->user->actions)
                ->showSubject()
                ->toArray(request()),
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

    public function testExceptionWithUnauthorised(): void
    {
        $newUser = AuthorisedUser::factory()->create();

        $this->expectException(AuthorizationException::class);
        $this->controller->actions($newUser->id, AuthorisedUser::class);
    }
}
