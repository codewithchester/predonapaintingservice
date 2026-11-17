<?php
namespace App\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) return;
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        session_name('peridona_session');
        session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => $secure, 'httponly' => true, 'samesite' => 'Lax']);
        session_start();
    }

    public static function flash(string $key, ?string $value = null): ?string
    {
        self::start();
        if ($value !== null) { $_SESSION['_flash'][$key] = $value; return null; }
        $message = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $message;
    }
}
