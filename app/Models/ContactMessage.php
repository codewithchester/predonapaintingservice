<?php
namespace App\Models;
use App\Core\Database;
final class ContactMessage
{
    public static function all(): array { return Database::connection()->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll(); }
    public static function find(int $id): ?array { $s=Database::connection()->prepare('SELECT * FROM contact_messages WHERE id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public static function markRead(int $id): void { $s=Database::connection()->prepare('UPDATE contact_messages SET is_read=1 WHERE id=?');$s->execute([$id]); }
    public static function create(array $d): void { $s=Database::connection()->prepare('INSERT INTO contact_messages(full_name,email,phone,subject,message) VALUES(?,?,?,?,?)');$s->execute([$d['full_name'],$d['email'],$d['phone']?:null,$d['subject']?:null,$d['message']]); }
}
