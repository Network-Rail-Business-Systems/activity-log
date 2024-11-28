<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit\ActivityHelper;

use NetworkRailBusinessSystems\ActivityLog\Activity;
use NetworkRailBusinessSystems\ActivityLog\app\Helpers\ActivityHelper;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\TestCase;

class LogStateTest extends TestCase
{
    protected Activity $activity;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->activity = ActivityHelper::logState($this->user, 'In Progress', 'Waiting');
    }

    public function testLogsStatus(): void
    {
        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $this->user->id,
            'event' => 'state',
            'description' => 'State changed to In Progress',
        ]);
    }

    public function testRecordsNewState(): void
    {
        $this->assertEquals('In Progress', $this->activity->getExtraProperty('attributes.state'));
    }

    public function testRecordsOldState(): void
    {
        $this->assertEquals('Waiting', $this->activity->getExtraProperty('old.state'));
    }
}
