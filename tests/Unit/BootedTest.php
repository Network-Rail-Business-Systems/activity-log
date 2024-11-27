<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit;

use NetworkRailBusinessSystems\ActivityLog\Activity;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\TestCase;


class BootedTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Activity::factory()
            ->count(3)
            ->forSubject($this->user)
            ->create();
    }

    public function testOrdersByNewest(): void
    {
        $activities = Activity::forSubject($this->user)->get();

        $this->assertEquals(
            $activities->sortByDesc('created_at')->pluck('id'),
            $activities->pluck('id'),
        );
    }
}
