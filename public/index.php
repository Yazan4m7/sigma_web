<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

$opsDashboardProfilePath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$shouldProfileOpsDashboard = $opsDashboardProfilePath === 'operations-dashboard';
$opsDashboardRequestStart = microtime(true);

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

$opsDashboardAfterAutoload = microtime(true);

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$opsDashboardAfterBootstrap = microtime(true);

$request = Request::capture();
$opsDashboardAfterCapture = microtime(true);

$response = $kernel->handle($request);
$opsDashboardAfterHandle = microtime(true);

$response->send();
$opsDashboardAfterSend = microtime(true);

$kernel->terminate($request, $response);
$opsDashboardAfterTerminate = microtime(true);

if ($shouldProfileOpsDashboard) {
    $responseBytes = null;
    if (method_exists($response, 'getContent')) {
        $content = $response->getContent();
        $responseBytes = is_string($content) ? strlen($content) : null;
    }

    $app->make('log')->info('[ops-dashboard] Bootstrap pipeline completed', [
        'path' => $opsDashboardProfilePath,
        'autoload_ms' => round(($opsDashboardAfterAutoload - $opsDashboardRequestStart) * 1000, 2),
        'bootstrap_ms' => round(($opsDashboardAfterBootstrap - $opsDashboardAfterAutoload) * 1000, 2),
        'request_capture_ms' => round(($opsDashboardAfterCapture - $opsDashboardAfterBootstrap) * 1000, 2),
        'kernel_handle_ms' => round(($opsDashboardAfterHandle - $opsDashboardAfterCapture) * 1000, 2),
        'response_send_ms' => round(($opsDashboardAfterSend - $opsDashboardAfterHandle) * 1000, 2),
        'terminate_ms' => round(($opsDashboardAfterTerminate - $opsDashboardAfterSend) * 1000, 2),
        'total_ms' => round(($opsDashboardAfterTerminate - $opsDashboardRequestStart) * 1000, 2),
        'response_bytes' => $responseBytes,
    ]);
}
