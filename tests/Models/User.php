<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\ActivityLog;
use NetworkRailBusinessSystems\ActivityLog\Tests\Database\Factories\UserFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements ActivityLog
{
    use CausesActivity;
    use LogsActivity;
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
        return route('users.index', $this);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function permission(): string|false
    {
        return 'manage';
    }
}
