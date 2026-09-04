<?php

// Ensure Vercel serverless writable directories exist in /tmp
$tmpStorageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/app/public',
    '/tmp/storage/app/private',
    '/tmp/bootstrap/cache'
];

foreach ($tmpStorageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Load Laravel Bootstrap & Process Request
require __DIR__ . '/../public/index.php';
