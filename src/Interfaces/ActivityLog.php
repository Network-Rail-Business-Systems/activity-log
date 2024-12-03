<?php

namespace NetworkRailBusinessSystems\ActivityLog\Interfaces;

interface ActivityLog
{
    public function backRoute(): string;

    public function label(): string;

    public function permission(): string|false;
}