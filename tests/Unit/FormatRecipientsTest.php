<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit;

use NetworkRailBusinessSystems\ActivityLog\ActivityHelper;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use Orchestra\Testbench\TestCase;

class FormatRecipientsTest extends TestCase
{
    public function testReturnsNoneWhenEmpty(): void
    {
        $this->assertEquals('none', ActivityHelper::formatRecipients([]));
    }

    public function testReturnsRecipient(): void
    {
        $this->assertEquals('Dan Quail', ActivityHelper::formatRecipients('Dan Quail'));
    }

    public function testReturnsRecipientsWhenArray(): void
    {
        $this->assertEquals(
            'Dan Quail (dan.quail@example.com), Jo Heron (jo.heron@example.com)',
            ActivityHelper::formatRecipients([
                'dan.quail@example.com' => 'Dan Quail',
                'jo.heron@example.com' => 'Jo Heron',
            ]),
        );
    }

    public function testReturnsRecipientsWhenArrayOfArrays(): void
    {
        $this->assertEquals(
            'Dan Quail (dan.quail@example.com), Jo Heron (jo.heron@example.com)',
            ActivityHelper::formatRecipients([
                [
                    'address' => 'dan.quail@example.com',
                    'name' => 'Dan Quail',
                ],
                [
                    'address' => 'jo.heron@example.com',
                    'name' => 'Jo Heron',
                ],
            ]),
        );
    }

    public function testReturnsRecipientsWhenCollection(): void
    {
        $users = User::factory()
            ->count(2)
            ->create();
        $userOne = $users->first();
        $userTwo = $users->last();

        $users->push('user-three');

        $this->assertEquals(
            "{$userOne->name} ({$userOne->email}), {$userTwo->name} ({$userTwo->email}), user-three",
            ActivityHelper::formatRecipients($users),
        );
    }
}
