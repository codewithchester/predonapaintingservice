<?php
namespace App\Models;
use App\Core\Database;
final class Testimonial
{
    public static function all(bool $publishedOnly=true): array { return Database::connection()->query('SELECT * FROM testimonials'.($publishedOnly?' WHERE is_published=1':'').' ORDER BY created_at DESC')->fetchAll(); }
    public static function save(array $d, ?int $id=null): void { $db=Database::connection();if($id){$s=$db->prepare('UPDATE testimonials SET customer_name=?,role_label=?,quote=?,is_published=? WHERE id=?');$s->execute([$d['customer_name'],$d['role_label'],$d['quote'],$d['is_published'],$id]);}else{$s=$db->prepare('INSERT INTO testimonials(customer_name,role_label,quote,is_published) VALUES(?,?,?,?)');$s->execute([$d['customer_name'],$d['role_label'],$d['quote'],$d['is_published']]);} }
    public static function delete(int $id): void { $s=Database::connection()->prepare('DELETE FROM testimonials WHERE id=?');$s->execute([$id]); }
}
