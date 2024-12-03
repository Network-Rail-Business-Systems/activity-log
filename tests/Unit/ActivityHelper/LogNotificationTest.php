<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit\ActivityHelper;

use NetworkRailBusinessSystems\ActivityLog\Activity;
use NetworkRailBusinessSystems\ActivityLog\ActivityHelper;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\TestCase;

class LogNotificationTest extends TestCase
{
    protected Activity $activity;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->activity = ActivityHelper::logNotification(
            $this->user,
            'Order Ready',
            [
                'cheese@example.com' => 'Fromage',
                'toast@example.com' => 'Dough',
            ],
            [
                'goose@example.com' => 'Honk',
                'duck@example.com' => 'Quack',
            ],
        );
    }

    public function testLogsStatus(): void
    {
        $this->assertDatabaseHas('activities', [
            'subject_id' => $this->user->id,
            'event' => 'notification',
            'description' => 'Order Ready notification sent',
        ]);
    }

    public function testRecordsToRecipients(): void
    {
        $this->assertEquals(
            'Fromage (cheese@example.com), Dough (toast@example.com)',
            $this->activity->getExtraProperty('attributes.to'),
        );
    }

    public function testRecordsCcRecipients(): void
    {
        $this->assertEquals(
            'Honk (goose@example.com), Quack (duck@example.com)',
            $this->activity->getExtraProperty('attributes.cc'),
        );
    }

    public function testDoesntWhenCcEmpty(): void
    {
        $this->activity = ActivityHelper::logNotification($this->user, 'Order Ready', [
            'cheese@example.com' => 'Fromage',
            'toast@example.com' => 'Dough',
        ]);

        $this->assertArrayNotHasKey('cc', $this->activity->getExtraProperty('attributes'));
    }
}
