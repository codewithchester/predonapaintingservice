<?php
declare(strict_types=1);

use App\Core\Env;
use App\Core\Session;

$root = dirname(__DIR__);
require $root . '/app/Core/Env.php';
Env::load($root . '/.env');

spl_autoload_register(function (string $class) use ($root): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) return;
    $file = $root . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) require $file;
});
require $root . '/app/Helpers/functions.php';

$app = require $root . '/config/app.php';
date_default_timezone_set($app['timezone']);
Session::start();

if ($app['env'] === 'production') {
    ini_set('display_errors', '0');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', $app['debug'] ? '1' : '0');
}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

try {
    $router = require $root . '/routes/web.php';
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
} catch (Throwable $e) {
    error_log('[Peridona] ' . $e->getMessage());
    http_response_code(500);
    if ($app['debug']) {
        echo '<main style="font:16px system-ui;padding:3rem;max-width:760px;margin:auto"><h1>Application error</h1><p>' . e($e->getMessage()) . '</p></main>';
    } else {
        view('pages/500', ['pageTitle' => 'Something went wrong']);
    }
}
