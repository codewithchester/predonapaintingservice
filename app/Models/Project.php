<?php
namespace App\Models;
use App\Core\Database;
final class Project
{
    public static function all(bool $publishedOnly=true): array { $where=$publishedOnly?'WHERE p.is_published=1':''; return Database::connection()->query("SELECT p.*, c.name AS category_name FROM projects p LEFT JOIN project_categories c ON c.id=p.category_id $where ORDER BY p.created_at DESC")->fetchAll(); }
    public static function find(int $id): ?array { $s=Database::connection()->prepare('SELECT p.*,c.name AS category_name FROM projects p LEFT JOIN project_categories c ON c.id=p.category_id WHERE p.id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public static function bySlug(string $slug): ?array { $s=Database::connection()->prepare('SELECT p.*,c.name AS category_name FROM projects p LEFT JOIN project_categories c ON c.id=p.category_id WHERE p.slug=? AND p.is_published=1');$s->execute([$slug]);return $s->fetch()?:null; }
    public static function save(array $d, ?int $id=null): void { $db=Database::connection(); $values=[$d['category_id']?:null,$d['title'],$d['slug'],$d['location'],$d['summary'],$d['description'],$d['image_path'],$d['before_image'],$d['after_image'],$d['is_featured'],$d['is_published']]; if($id){$s=$db->prepare('UPDATE projects SET category_id=?,title=?,slug=?,location=?,summary=?,description=?,image_path=?,before_image=?,after_image=?,is_featured=?,is_published=?,updated_at=NOW() WHERE id=?');$s->execute([...$values,$id]);}else{$s=$db->prepare('INSERT INTO projects(category_id,title,slug,location,summary,description,image_path,before_image,after_image,is_featured,is_published) VALUES(?,?,?,?,?,?,?,?,?,?,?)');$s->execute($values);} }
    public static function delete(int $id): void { $s=Database::connection()->prepare('DELETE FROM projects WHERE id=?');$s->execute([$id]); }
}
