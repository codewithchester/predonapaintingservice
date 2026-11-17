<?php
/** Create the first administrator from the command line only. */
if (PHP_SAPI !== 'cli') { http_response_code(403); exit("This script can only be run from the command line.\n"); }
$root = dirname(__DIR__);
require $root . '/app/Core/Env.php';
App\Core\Env::load($root . '/.env');
spl_autoload_register(function(string $class) use ($root): void {
    if (!str_starts_with($class, 'App\\')) return;
    $file = $root . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($file)) require $file;
});

// Load shared helper functions, including env(), before reading configuration.
require_once $root . '/app/Helpers/functions.php';

$config = require $root . '/config/database.php';
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $config['host'], $config['port'], $config['database'], $config['charset']);
try {
    $db = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $count = (int) $db->query('SELECT COUNT(*) FROM administrators')->fetchColumn();
    if ($count > 0) { fwrite(STDERR, "An administrator already exists. Use the admin dashboard to manage access.\n"); exit(1); }
    $name = $argv[1] ?? '';
    $email = $argv[2] ?? '';
    $password = $argv[3] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) {
        fwrite(STDERR, "Usage: php database/create_admin.php \"Full Name\" admin@example.com \"StrongPasswordAtLeast12Chars\"\n");
        exit(1);
    }
    $roleId = $db->query("SELECT id FROM roles WHERE name='Super Administrator' LIMIT 1")->fetchColumn();
    $stmt = $db->prepare('INSERT INTO administrators(role_id,name,email,password_hash,is_active) VALUES(?,?,?,?,1)');
    $stmt->execute([$roleId ?: null, $name, strtolower($email), password_hash($password, PASSWORD_DEFAULT)]);
    fwrite(STDOUT, "Administrator created successfully. Sign in at /admin/login.\n");
} catch (Throwable $e) {
    fwrite(STDERR, "Could not create administrator: " . $e->getMessage() . "\n");
    exit(1);
}
