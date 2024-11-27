<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //TODO GOVUK SERVICE PROVIDER
    }

    public function boot(): void
    {
        $this->bootPublishes();
        $this->bootRoutes();
        $this->bootViews();
    }

    protected function bootPublishes(): void
    {
        //
    }

    protected function bootRoutes(): void
    {
        //
    }

    protected function bootViews(): void
    {
        //
    }
}
