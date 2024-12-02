<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/govuk-activity-log.php',
            'govuk-activity-log',
        );
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
            __DIR__ . '/govuk-activity-log.php' => config_path('govuk-activity-log.php'),
        ], 'govuk-activity-log');

        $this->publishes([
            __DIR__ . '/Views/activity.blade.php' => resource_path('views/vendor/govuk-activity-log/activity.blade.php'),
        ], 'govuk-activity-log-views');
    }

    protected function bootRoutes(): void
    {
        //
    }

    protected function bootViews(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/Views/activity.blade.php',
            'govuk-activity-log',
        );
    }
}
