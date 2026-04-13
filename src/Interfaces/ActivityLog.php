<?php

namespace NetworkRailBusinessSystems\ActivityLog\Interfaces;

use BackedEnum;

interface ActivityLog
{
    public function backRoute(): string;

    public function label(): string;

    public function permission(): BackedEnum|string|false;
}
