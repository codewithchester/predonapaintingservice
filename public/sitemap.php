<?php
require dirname(__DIR__) . '/app/Core/Env.php';
App\Core\Env::load(dirname(__DIR__) . '/.env');
$base = rtrim((string) (getenv('APP_URL') ?: ''), '/');
if ($base === '') {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $directory = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $base = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($directory === '' || $directory === '/' ? '' : $directory);
}
$paths = ['', 'about', 'services', 'projects', 'process', 'contact', 'quote'];
$dynamic = [];
try {
    $config = require dirname(__DIR__) . '/config/database.php';
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $config['host'], $config['port'], $config['database'], $config['charset']);
    $db = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
    $dynamic = $db->query("SELECT CONCAT('services/',slug) AS path FROM services WHERE is_published=1 UNION SELECT CONCAT('projects/',slug) AS path FROM projects WHERE is_published=1")->fetchAll(PDO::FETCH_COLUMN);
} catch (Throwable $e) { error_log('[Peridona sitemap] '.$e->getMessage()); }
header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach (array_merge($paths, $dynamic) as $path) {
    $loc = $base . ($path === '' ? '/' : '/' . $path);
    echo '<url><loc>' . htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</loc></url>' . "\n";
}
echo "</urlset>\n";
