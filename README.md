# Activity-Log-Library

![Composer status](.github/composer.svg)
![Coverage status](.github/coverage.svg)
![NPM status](.github/npm.svg)
![PHP version](.github/php.svg)
![Tests status](.github/tests.svg)

Use the Activity Log library to add common activity log components for GOV.UK systems

## What's in the box?
* PHP 8.3
* Laravel 11 Blade Activity Log Page in the GOV.UK Design
  
## Installation
Via Composer: `composer require networkrailbusinesssystems/activity-log`

## Publish files (Optional)
All required files can be published with the command:
`php artisan vendor:publish --provider="NetworkRailBusinessSystems\ActivityLog\ActivityLogServiceProvider" --tag="activity-log"`

### govuk-activity-log 
This tag will publish the config:
* /govuk-activity-log.php

### govuk-activity-log-views
This tag will publish the blade view:
* /Views/activity.blade.php

## Set-up
In your web.php (or your standard route file) add the lines:  
`Route::activityLogActioner(YourActionerClass)`, `Route::activityLogActioned(YourActionedClass)` in the route path.   

When calling this route in your blade.php, make sure to pass the id. e.g.   
`route('your.route', $your_user->id)`  

On the Model `YourActionerClass` and `YourActionedClass`, implement Actioned, Actioner + use the HasActions and HasActivities traits

## Pre-requisites
The Activity Log requires the [GOVUK Laravel Forms Route Macro](https://github.com/AnthonyEdmonds/govuk-laravel/blob/main/docs/forms.md).

## Routing
A Route Macro is provided to handle the Controller routing

# Configuration
|   Option   | Type  |         Default         |           Usage         |
| -----------| ----  | ------------------------| ------------------------|
| user_model | model | App\Models\User:: class | Set the User Model to use



