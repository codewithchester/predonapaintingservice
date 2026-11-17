<?php
namespace App\Models;

use App\Core\Database;

final class Admin
{
    public static function find(int $id): ?array
    {
        $s = Database::connection()->prepare('SELECT a.id,a.name,a.email,a.role_id,a.is_active,a.last_login_at,a.created_at,r.name AS role_name FROM administrators a LEFT JOIN roles r ON r.id=a.role_id WHERE a.id=? AND a.is_active=1 LIMIT 1');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $s = Database::connection()->prepare('SELECT * FROM administrators WHERE email=? AND is_active=1 LIMIT 1');
        $s->execute([strtolower(trim($email))]);
        return $s->fetch() ?: null;
    }

    public static function touchLogin(int $id): void
    {
        $s = Database::connection()->prepare('UPDATE administrators SET last_login_at=NOW() WHERE id=?');
        $s->execute([$id]);
    }

    public static function allForManagement(): array
    {
        return Database::connection()->query('SELECT a.id,a.name,a.email,a.role_id,a.is_active,a.last_login_at,a.created_at,r.name AS role_name FROM administrators a LEFT JOIN roles r ON r.id=a.role_id ORDER BY a.created_at DESC')->fetchAll();
    }

    public static function roles(): array
    {
        return Database::connection()->query('SELECT id,name,description FROM roles ORDER BY CASE name WHEN "Super Administrator" THEN 1 WHEN "Content Manager" THEN 2 WHEN "Enquiry Manager" THEN 3 ELSE 4 END,name')->fetchAll();
    }

    public static function managementRecord(int $id): ?array
    {
        $s = Database::connection()->prepare('SELECT a.id,a.name,a.email,a.role_id,a.is_active,a.last_login_at,a.created_at,r.name AS role_name FROM administrators a LEFT JOIN roles r ON r.id=a.role_id WHERE a.id=? LIMIT 1');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public static function emailExists(string $email, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM administrators WHERE email=?';
        $params = [strtolower(trim($email))];
        if ($exceptId !== null) { $sql .= ' AND id<>?'; $params[] = $exceptId; }
        $s = Database::connection()->prepare($sql);
        $s->execute($params);
        return (int)$s->fetchColumn() > 0;
    }

    public static function roleExists(int $roleId): bool
    {
        $s = Database::connection()->prepare('SELECT COUNT(*) FROM roles WHERE id=?');
        $s->execute([$roleId]);
        return (int)$s->fetchColumn() === 1;
    }

    public static function roleName(int $roleId): ?string
    {
        $s = Database::connection()->prepare('SELECT name FROM roles WHERE id=? LIMIT 1');
        $s->execute([$roleId]);
        $name = $s->fetchColumn();
        return $name === false ? null : (string)$name;
    }

    public static function create(array $data): int
    {
        $s = Database::connection()->prepare('INSERT INTO administrators(role_id,name,email,password_hash,is_active) VALUES(?,?,?,?,?)');
        $s->execute([$data['role_id'], $data['name'], strtolower($data['email']), $data['password_hash'], $data['is_active']]);
        return (int)Database::connection()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        if (!empty($data['password_hash'])) {
            $s = Database::connection()->prepare('UPDATE administrators SET role_id=?,name=?,email=?,password_hash=?,is_active=? WHERE id=?');
            $s->execute([$data['role_id'], $data['name'], strtolower($data['email']), $data['password_hash'], $data['is_active'], $id]);
            return;
        }
        $s = Database::connection()->prepare('UPDATE administrators SET role_id=?,name=?,email=?,is_active=? WHERE id=?');
        $s->execute([$data['role_id'], $data['name'], strtolower($data['email']), $data['is_active'], $id]);
    }

    public static function updateStatus(int $id, bool $active): void
    {
        $s = Database::connection()->prepare('UPDATE administrators SET is_active=? WHERE id=?');
        $s->execute([$active ? 1 : 0, $id]);
    }

    public static function activeSuperAdminCount(?int $exceptId = null): int
    {
        $sql = 'SELECT COUNT(*) FROM administrators a INNER JOIN roles r ON r.id=a.role_id WHERE r.name="Super Administrator" AND a.is_active=1';
        $params = [];
        if ($exceptId !== null) { $sql .= ' AND a.id<>?'; $params[] = $exceptId; }
        $s = Database::connection()->prepare($sql);
        $s->execute($params);
        return (int)$s->fetchColumn();
    }
}
