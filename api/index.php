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

foreach ([
    $storagePath.'/app/public',
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

if (file_exists($maintenance = $storagePath.'/framework/maintenance.php')) {
    require $maintenance;
}

require $basePath.'/vendor/autoload.php';

(require_once $basePath.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
