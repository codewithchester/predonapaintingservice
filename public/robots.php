<?php
require dirname(__DIR__) . '/app/Core/Env.php';
App\Core\Env::load(dirname(__DIR__) . '/.env');
$base = rtrim((string) (getenv('APP_URL') ?: ''), '/');
if ($base === '') {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $directory = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $base = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($directory === '' || $directory === '/' ? '' : $directory);
}
header('Content-Type: text/plain; charset=utf-8');
echo "User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: " . $base . "/sitemap.xml\n";
