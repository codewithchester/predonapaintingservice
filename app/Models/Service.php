<?php
namespace App\Models;
use App\Core\Database;
final class Service
{
    public static function all(bool $publishedOnly=true): array { $sql='SELECT * FROM services'; if($publishedOnly)$sql.=' WHERE is_published=1'; return Database::connection()->query($sql.' ORDER BY sort_order ASC, title ASC')->fetchAll(); }
    public static function find(int $id): ?array { $s=Database::connection()->prepare('SELECT * FROM services WHERE id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public static function bySlug(string $slug): ?array { $s=Database::connection()->prepare('SELECT * FROM services WHERE slug=? AND is_published=1');$s->execute([$slug]);return $s->fetch()?:null; }
    public static function save(array $d, ?int $id=null): void { $db=Database::connection(); if($id){$s=$db->prepare('UPDATE services SET title=?,slug=?,short_description=?,description=?,icon=?,image_path=?,is_published=?,sort_order=?,updated_at=NOW() WHERE id=?');$s->execute([$d['title'],$d['slug'],$d['short_description'],$d['description'],$d['icon'],$d['image_path'],$d['is_published'],$d['sort_order'],$id]);}else{$s=$db->prepare('INSERT INTO services(title,slug,short_description,description,icon,image_path,is_published,sort_order) VALUES(?,?,?,?,?,?,?,?)');$s->execute([$d['title'],$d['slug'],$d['short_description'],$d['description'],$d['icon'],$d['image_path'],$d['is_published'],$d['sort_order']]);} }
    public static function delete(int $id): void { $s=Database::connection()->prepare('DELETE FROM services WHERE id=?');$s->execute([$id]); }
}
