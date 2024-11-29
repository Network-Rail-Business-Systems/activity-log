<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NetworkRailBusinessSystems\ActivityLog\Tests\Database\Factories\UserFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Model
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

}
