<?php
namespace App\Core;

use App\Models\Admin;

final class Auth
{
    public static function user(): ?array
    {
        Session::start();
        if (empty($_SESSION['admin_id'])) return null;
        return Admin::find((int) $_SESSION['admin_id']);
    }

    public static function check(): bool { return self::user() !== null; }

    public static function can(string $permission): bool
    {
        $admin = self::user();
        if (!$admin || empty($admin['role_id'])) return false;
        $stmt = Database::connection()->prepare('SELECT 1 FROM role_permissions rp INNER JOIN permissions p ON p.id=rp.permission_id WHERE rp.role_id=? AND p.permission_key=? LIMIT 1');
        $stmt->execute([(int)$admin['role_id'], $permission]);
        return (bool) $stmt->fetchColumn();
    }

    public static function attempt(string $email, string $password): bool
    {
        $admin = Admin::findByEmail($email);
        if (!$admin || !password_verify($password, $admin['password_hash'])) return false;
        Session::start();
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_last_activity'] = time();
        Admin::touchLogin((int) $admin['id']);
        return true;
    }

    public static function logout(): void
    {
        Session::start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool) $params['secure'], (bool) $params['httponly']);
        }
        session_destroy();
    }
}
