<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Vercel's serverless filesystem is read-only except for /tmp, and /tmp
// itself is only guaranteed for the lifetime of a single function instance
// (not shared across invocations or deployments). Laravel still needs
// *somewhere* writable to boot — for framework/views and framework/cache
// compilation and, if ever enabled, file logs — so storage_path() is
// redirected there. This does NOT make it a place to persist real data:
// uploaded files and the database must live in external services (S3/R2
// and a managed Postgres/MySQL instance) configured via environment
// variables — see config/filesystems.php and config/database.php.
$directories = [
    '/tmp/storage/app/private',
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        @mkdir($directory, 0755, true);
    }
}

// Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Force Laravel to use /tmp/storage for logs, sessions, views, and cache
$app->useStoragePath('/tmp/storage');

// Capture and handle the HTTP request. handleRequest() already sends the
// response and terminates the kernel internally (same as public/index.php)
// — capturing its return value and calling ->send() again would crash with
// "Call to a member function send() on null" after the real response had
// already been flushed to the client.
$app->handleRequest(Request::capture());
