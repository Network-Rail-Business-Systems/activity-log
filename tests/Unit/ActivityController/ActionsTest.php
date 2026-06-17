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
        $this->controller->id = $this->user->id;
        $this->controller->class = User::class;
    }

    public function test(): void
    {
        $this->response = $this->controller->actions();

        $this->assertEquals('govuk-activity-log::activity', $this->response->getData()['content']);

        $this->assertEquals(
            ActivityCollection::make($this->user->actions)
                ->showSubject()
                ->toArray(request()),
            $this->response->getData()['activities'],
        );

        $this->assertEquals(route('admin.users.show', $this->user), $this->response->getData()['back']);

        $this->assertTrue($this->response->getData()['showSubject']);

        $this->assertEquals($this->user->id, $this->response->getData()['subject']->id);

        $this->assertEquals(
            "Activities performed by {$this->user->name}",
            $this->response->getData()['title'],
        );
    }

    public function testExceptionWithUnauthorised(): void
    {
        $this->expectException(AuthorizationException::class);

        $this->controller->class = AuthorisedUser::class;

        $this->controller->actions();
    }
}
