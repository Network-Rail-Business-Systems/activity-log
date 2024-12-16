<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioned;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioner;
use NetworkRailBusinessSystems\ActivityLog\Tests\Database\Factories\UserFactory;
use NetworkRailBusinessSystems\ActivityLog\Traits\HasActions;
use NetworkRailBusinessSystems\ActivityLog\Traits\HasActivities;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Actioner, Actioned
{
    use CausesActivity;
    use LogsActivity;
    use HasActions;
    use HasActivities;
    use HasFactory;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logFillable();
    }

    protected static function newFactory(): UserFactory
    {
        return new UserFactory();
    }

    public function backRoute(): string
    {
        return route('admin.users.show', $this);
    }

    public function label(): string
    {
        return $this->name;
    }

    public function permission(): string|false
    {
        return false;
    }
}
