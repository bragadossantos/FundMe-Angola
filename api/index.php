<?php

// Prepare writable directories in /tmp for Vercel Serverless environment
$directories = [
    '/tmp/storage/app/private',
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        @mkdir($directory, 0755, true);
    }
}

// Set environment variable for storage
putenv('APP_STORAGE=/tmp/storage');

// Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Force Laravel to use /tmp/storage for logs, sessions, views, and cache
$app->useStoragePath('/tmp/storage');

// Capture and process the HTTP request
$request = \Illuminate\Http\Request::capture();
$response = $app->handleRequest($request);
$response->send();
