<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug: check if key files exist
if (!file_exists(__DIR__.'/../vendor/autoload.php')) {
    die('vendor/autoload.php missing');
}
if (!file_exists(__DIR__.'/../bootstrap/app.php')) {
    die('bootstrap/app.php missing');
}
if (!is_writable(__DIR__.'/../storage')) {
    die('storage not writable');
}

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());