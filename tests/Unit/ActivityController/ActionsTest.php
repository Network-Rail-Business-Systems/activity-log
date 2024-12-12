<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit\ActivityController;

use Illuminate\Contracts\View\View;
use NetworkRailBusinessSystems\ActivityLog\ActivityCollection;
use NetworkRailBusinessSystems\ActivityLog\ActivityController;
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
        $model = '\NetworkRailBusinessSystems\ActivityLog\Tests\Models\User';
        config()->set('govuk-activity-log.user_model', $model);

//             __DIR__.'../../Models';

        $this->controller = new ActivityController();
//        dd($this->controller->actions($this->user->id,$this->user));
//        dd('Does it get here' . $this->controller->actions($this->user->id, $this->user)); NO IT DOES NOT
        $this->response = $this->controller->actions($this->user->id, $model);
//        dd('This is reponse: '.$this->response); Doesnt reach here
//        dd(100000);
    }

    public function testReturnsView(): void
    {
        $this->assertEquals('activity', $this->response->getData()['content']);
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
}
