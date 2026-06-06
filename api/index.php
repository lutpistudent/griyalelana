<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$basePath = dirname(__DIR__);
$storagePath = $_ENV['LARAVEL_STORAGE_PATH']
    ?? $_SERVER['LARAVEL_STORAGE_PATH']
    ?? getenv('LARAVEL_STORAGE_PATH')
    ?: '/tmp/storage';

$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;
putenv("LARAVEL_STORAGE_PATH={$storagePath}");

foreach ([
    'SESSION_DRIVER' => 'cookie',
    'CACHE_STORE' => 'array',
    'QUEUE_CONNECTION' => 'sync',
    'LOG_CHANNEL' => 'stderr',
    'LOG_STACK' => 'stderr',
] as $key => $value) {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("{$key}={$value}");
}

foreach ([
    $storagePath.'/app/public',
    $storagePath.'/bootstrap/cache',
    $storagePath.'/framework/cache/data',
    $storagePath.'/framework/sessions',
    $storagePath.'/framework/testing',
    $storagePath.'/framework/views',
    $storagePath.'/logs',
] as $directory) {
    if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
        throw new RuntimeException("Directory [{$directory}] could not be created.");
    }
}

$cachePaths = [
    'APP_CONFIG_CACHE' => $storagePath.'/bootstrap/cache/config.php',
    'APP_EVENTS_CACHE' => $storagePath.'/bootstrap/cache/events.php',
    'APP_PACKAGES_CACHE' => $storagePath.'/bootstrap/cache/packages.php',
    'APP_ROUTES_CACHE' => $storagePath.'/bootstrap/cache/routes.php',
    'APP_SERVICES_CACHE' => $storagePath.'/bootstrap/cache/services.php',
    'VIEW_COMPILED_PATH' => $storagePath.'/framework/views',
];

foreach ($cachePaths as $key => $path) {
    $_ENV[$key] = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $path;
    $_SERVER[$key] = $_ENV[$key];
    putenv("{$key}={$_ENV[$key]}");
}

if (file_exists($maintenance = $storagePath.'/framework/maintenance.php')) {
    require $maintenance;
}

require $basePath.'/vendor/autoload.php';

(require_once $basePath.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
