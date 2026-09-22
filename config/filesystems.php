<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK') ?: 'local',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => (function () {
        // Computed once, and using ?: rather than env(..., 'local') so a
        // platform env var that's present but left blank (seen on Vercel —
        // the same issue as config/app.php's 'timezone') doesn't silently
        // resolve to a driver literally named ''.
        $localDriver = env('FILESYSTEM_LOCAL_DRIVER') ?: 'local';
        $publicDriver = env('FILESYSTEM_PUBLIC_DRIVER') ?: 'local';

        return [

            // On a normal server (Docker/Render, local dev) this stays a
            // plain local disk. On a serverless host like Vercel there is no
            // persistent filesystem, so FILESYSTEM_LOCAL_DRIVER=s3 redirects
            // private document storage to an S3-compatible bucket instead,
            // without any controller code needing to change — they all just
            // use Storage::disk('local').
            'local' => [
                'driver' => $localDriver,
                'root' => $localDriver === 's3'
                    ? env('AWS_ROOT_PRIVATE', 'private')
                    : storage_path('app/private'),
                'visibility' => 'private',
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket' => env('AWS_BUCKET_PRIVATE', env('AWS_BUCKET')),
                'endpoint' => env('AWS_ENDPOINT'),
                'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
                'serve' => true,
                'throw' => false,
                'report' => false,
            ],

            // Same idea for public assets (campaign featured images). When
            // this is 's3', asset URLs must be built with
            // Storage::disk('public')->url() rather than asset('storage/...')
            // — the public/storage symlink only exists (and only needs to)
            // when this stays on the local driver.
            'public' => [
                'driver' => $publicDriver,
                'root' => $publicDriver === 's3'
                    ? env('AWS_ROOT_PUBLIC', 'public')
                    : storage_path('app/public'),
                'url' => $publicDriver === 's3'
                    ? env('AWS_URL')
                    : env('APP_URL').'/storage',
                'visibility' => 'public',
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket' => env('AWS_BUCKET_PUBLIC', env('AWS_BUCKET')),
                'endpoint' => env('AWS_ENDPOINT'),
                'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
                'throw' => false,
                'report' => false,
            ],

            's3' => [
                'driver' => 's3',
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket' => env('AWS_BUCKET'),
                'url' => env('AWS_URL'),
                'endpoint' => env('AWS_ENDPOINT'),
                'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
                'throw' => false,
                'report' => false,
            ],

        ];
    })(),

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
