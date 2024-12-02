<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioned;
use NetworkRailBusinessSystems\ActivityLog\Interfaces\Actioner;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/activity-log.php', 'activity-log');
    }

    public function boot(): void
    {
        $this->bootPublishes();
        $this->bootRoutes();
        $this->bootViews();
    }

    protected function bootPublishes(): void
    {
        $this->publishes([
            __DIR__ . '/activity-log.php' => config_path('activity-log.php'),
        ], 'activity-log-config');

        $this->publishes([
            __DIR__ . '/activity.blade.php' => resource_path('views/vendor/activity-log'),
        ], 'activity-log-blade');
    }

    protected function bootRoutes(): void
    {
        // TODO Make Route macro for activity controller
        // TODO Must be able to add custom activity routing

        /** @param class-string<Actioner> $model */
        Route::macro('activityLogActioner', function (string $model) {
            Route::get('/{id}/actions', [
                'as' => 'actions',
                'class' => $model,
                'uses' => ActivityController::class . '@actions',
            ]);
        });

        /** @param class-string<Actioned> $model */
        Route::macro('activityLogActioned', function (string $model) {
            Route::get('/{id}/activities', [
                'as' => 'activities',
                'class' => $model,
                'uses' => ActivityController::class . '@activities',
            ]);
        });
    }

    protected function bootViews(): void
    {
        $this->loadViewsFrom(
            __DIR__ . 'activity.blade.php',
            'activity-log',
        );
    }
}
