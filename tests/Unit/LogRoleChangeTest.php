<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit;

use NetworkRailBusinessSystems\ActivityLog\Activity;
use NetworkRailBusinessSystems\ActivityLog\ActivityHelper;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use Orchestra\Testbench\TestCase;

class LogRoleChangeTest extends TestCase
{
    protected Activity $activity;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->activity = ActivityHelper::logRoleChange($this->user, 'Admin', 'granted');
    }

    public function testLogsStatus(): void
    {
        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $this->user->id,
            'event' => 'role',
            'description' => 'Admin Role granted',
        ]);
    }
}
