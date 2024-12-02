<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

/**
 * @mixin SpatieActivity
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
