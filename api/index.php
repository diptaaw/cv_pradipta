<?php

// Ensure serverless temporary storage directories exist in /tmp
$storagePaths = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// Redirect application storage and compiled views to writable /tmp
putenv('APP_STORAGE_PATH=/tmp/storage');
$_ENV['APP_STORAGE_PATH'] = '/tmp/storage';
$_SERVER['APP_STORAGE_PATH'] = '/tmp/storage';

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// Prevent writes to read-only bootstrap/cache
$cacheVars = [
    'APP_SERVICES_CACHE' => '/tmp/storage/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/storage/packages.php',
    'APP_CONFIG_CACHE' => '/tmp/storage/config.php',
    'APP_ROUTES_CACHE' => '/tmp/storage/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/storage/events.php',
];

foreach ($cacheVars as $key => $val) {
    putenv("{$key}={$val}");
    $_ENV[$key] = $val;
    $_SERVER[$key] = $val;
}

if (!getenv('LOG_CHANNEL')) {
    putenv('LOG_CHANNEL=stderr');
    $_ENV['LOG_CHANNEL'] = 'stderr';
    $_SERVER['LOG_CHANNEL'] = 'stderr';
}

// Respect APP_DEBUG from environment, default to false if unset
if (getenv('APP_DEBUG') === false) {
    putenv('APP_DEBUG=false');
    $_ENV['APP_DEBUG'] = 'false';
    $_SERVER['APP_DEBUG'] = 'false';
}

// Normalize SCRIPT_NAME and SCRIPT_FILENAME so Laravel does not strip /api prefix from routes
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// Forward Vercel requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
