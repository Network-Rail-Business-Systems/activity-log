<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests;

use AnthonyEdmonds\LaravelTestingTraits\SignsInUsers;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use SignsInUsers;

    protected function setUp(): void
    {
        parent::setUp();
    }

}
