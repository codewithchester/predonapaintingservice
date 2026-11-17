<?php
namespace App\Models;
use App\Core\Database;
final class Enquiry
{
    public static function create(array $d): int { $s=Database::connection()->prepare('INSERT INTO enquiries(reference_code,full_name,phone,email,location,property_type,service_type,project_size,preferred_date,message,attachment_path,status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)');$s->execute([$d['reference_code'],$d['full_name'],$d['phone'],$d['email']?:null,$d['location']?:null,$d['property_type']?:null,$d['service_type']?:null,$d['project_size']?:null,$d['preferred_date']?:null,$d['message'],$d['attachment_path']?:null,'pending']);return (int)Database::connection()->lastInsertId(); }
    public static function all(): array { return Database::connection()->query('SELECT * FROM enquiries ORDER BY created_at DESC')->fetchAll(); }
    public static function find(int $id): ?array { $s=Database::connection()->prepare('SELECT * FROM enquiries WHERE id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public static function updateStatus(int $id,string $status): void { $s=Database::connection()->prepare('UPDATE enquiries SET status=? WHERE id=?');$s->execute([$status,$id]); }
}
