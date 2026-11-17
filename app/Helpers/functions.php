<?php
use App\Core\CSRF;
use App\Core\Database;
use App\Core\Session;

function env(string $key, mixed $default = null): mixed { $value = getenv($key); return $value === false ? $default : $value; }
function e(mixed $value): string { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function base_url(): string
{
    $configured = (string) env('APP_URL', '');
    if ($configured !== '') return rtrim($configured, '/');
    $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    return rtrim($base === '/' ? '' : $base, '/');
}
function url(string $path = ''): string { return base_url() . '/' . ltrim($path, '/'); }
function asset(string $path): string { return url('assets/' . ltrim($path, '/')); }
function current_url(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    if ($base !== '' && $base !== '/' && str_starts_with($path, $base)) $path = substr($path, strlen($base));
    return url(ltrim($path, '/'));
}
function redirect(string $path): never { header('Location: ' . (str_starts_with($path, 'http') ? $path : url($path))); exit; }
function csrf_field(): string { return '<input type="hidden" name="_token" value="' . e(CSRF::token()) . '">'; }
function flash(string $key, ?string $value = null): ?string { return Session::flash($key, $value); }
function setting(string $key, string $default = ''): string
{
    static $settings = null;
    if ($settings === null) {
        try { $settings = Database::connection()->query('SELECT setting_key, setting_value FROM website_settings')->fetchAll(PDO::FETCH_KEY_PAIR); }
        catch (Throwable) { $settings = []; }
    }
    return (string) ($settings[$key] ?? $default);
}
function view(string $template, array $data = [], string $layout = 'main'): void
{
    extract($data, EXTR_SKIP);
    $templateFile = dirname(__DIR__, 2) . '/resources/views/' . $template . '.php';
    if (!is_file($templateFile)) { http_response_code(500); echo 'Page template unavailable.'; return; }
    ob_start();
    require $templateFile;
    $content = ob_get_clean();
    $layoutFile = dirname(__DIR__, 2) . '/resources/views/layouts/' . $layout . '.php';
    if (is_file($layoutFile)) require $layoutFile; else echo $content;
}
function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-') ?: bin2hex(random_bytes(4));
}
function format_date(?string $value): string { return $value ? date('M j, Y', strtotime($value)) : '—'; }
function whatsapp_url(string $message = 'Hello Peridona Painting Service, I would like to enquire about your painting services.'): string
{
    $number = preg_replace('/\D+/', '', setting('whatsapp_number', '233547320593'));
    if (str_starts_with($number, '0')) $number = '233' . substr($number, 1);
    return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
}
function old(string $key, string $default = ''): string
{
    return e($_SESSION['_old'][$key] ?? $default);
}
function clear_old(): void { unset($_SESSION['_old']); }
