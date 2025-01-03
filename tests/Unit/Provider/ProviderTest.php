<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Unit\Provider;

use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;
use NetworkRailBusinessSystems\ActivityLog\Tests\TestCase;

class ProviderTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->signIn($this->user);
    }

    public function testActioned(): void
    {
        $response = $this->get(route('activities', ['id' => $this->user->id]));

        $response->assertStatus(200);
    }
}
