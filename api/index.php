<?php

/**
 * Vercel Serverless Entry Point for Laravel
 *
 * Handles all incoming requests on Vercel's serverless PHP runtime.
 * The vercel-php runtime automatically runs composer install.
 */

// Set writable paths for serverless environment
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['CACHE_STORE'] = 'array';

// Ensure tmp directories exist
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}
if (!is_dir('/tmp/cache')) {
    mkdir('/tmp/cache', 0755, true);
}
if (!is_dir('/tmp/sessions')) {
    mkdir('/tmp/sessions', 0755, true);
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override storage path for serverless
$app->useStoragePath('/tmp/storage');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
