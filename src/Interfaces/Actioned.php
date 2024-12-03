<?php

namespace NetworkRailBusinessSystems\ActivityLog\Interfaces;


use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Actioned extends ActivityLog
{
    public function activities(): MorphMany;

    public function viewActivities(): View;
}