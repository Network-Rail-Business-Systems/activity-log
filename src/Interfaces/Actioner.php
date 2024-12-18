<?php

namespace NetworkRailBusinessSystems\ActivityLog\Interfaces;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Actioner extends ActivityLog
{
    public function actions(): MorphMany;

    public function viewActions(): View;
}
