<?php
namespace App\Core;

final class CSRF
{
    public static function token(): string
    {
        Session::start();
        if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        return $_SESSION['_csrf'];
    }

    public static function verify(?string $token): bool
    {
        Session::start();
        return is_string($token) && isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
    }
}
