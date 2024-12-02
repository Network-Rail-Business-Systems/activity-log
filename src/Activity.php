<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

/**
 * @property ?Model $causer //TODO use a mixin
 */
class Activity extends SpatieActivity
{
    use HasFactory;

    protected $perPage = 10;

    // Setup
    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('orderByNewest', function (Builder $query) {
            $query->orderBy('created_at', 'desc');
        });
    }

    protected static function newFactory(): ActivityFactory
    {
        return new ActivityFactory();
    }
}
