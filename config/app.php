<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL') ?: 'http://localhost',

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    // env('APP_TIMEZONE', 'UTC') would silently keep an empty string if the
    // hosting platform has the variable present-but-blank (seen on Vercel) —
    // env()'s default only kicks in when the variable is truly unset, and
    // date_default_timezone_set('') breaks app boot. The `?:` fallback below
    // treats blank the same as unset. Same reasoning applies to every other
    // env(..., default) call in config/ that selects a driver/connection.
    'timezone' => env('APP_TIMEZONE') ?: 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE') ?: 'en',

    'fallback_locale' => env('APP_FALLBACK_LOCALE') ?: 'en',

    'faker_locale' => env('APP_FAKER_LOCALE') ?: 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    // Deliberately no fallback key here. A hardcoded default would be
    // committed to the repo and therefore known to anyone with the source —
    // every deployment that forgot to set a real APP_KEY would silently
    // share the same encryption key for sessions/cookies. Laravel's normal
    // behavior (throwing if APP_KEY is missing) is what we want instead.
    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Deployment Migration Token
    |--------------------------------------------------------------------------
    |
    | On a serverless host (e.g. Vercel) there is no shell to run
    | `php artisan migrate` after a deploy the way the Docker/Render
    | entrypoint does automatically. DeploymentController exposes a
    | migration-runner route guarded by this token instead. Leave it unset
    | to disable the route entirely (it always aborts with 403 if empty).
    |
    */

    'deploy_token' => env('DEPLOY_MIGRATE_TOKEN'),

];
