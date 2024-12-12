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
//        Anything you need for testing you dont add here
//        Anything you use for the application go here/use WILL BE USED EVERYWHERE
//        Testing the package, YOU DO NOT ADD TO SERVICE PROVIDER
//        Create routes in testcase but you do not need to put macros in the service provider
//        User.php is only for testing this package and not the overall package as it would be different for everyone
//


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
                'uses' => ActivityController::class . '@activity',
            ]);
        });
    }


    protected function bootViews(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/Views/activity.blade.php',
            'govuk-activity-log',
        );
    }
}
