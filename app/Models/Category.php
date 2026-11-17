<?php
namespace App\Models;
use App\Core\Database;
final class Category
{
    public static function all(): array { return Database::connection()->query('SELECT c.*, COUNT(p.id) project_count FROM project_categories c LEFT JOIN projects p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name')->fetchAll(); }
    public static function create(string $name): void { $s=Database::connection()->prepare('INSERT INTO project_categories(name,slug) VALUES(?,?)');$s->execute([$name,\slugify($name)]); }
    public static function delete(int $id): void { $s=Database::connection()->prepare('DELETE FROM project_categories WHERE id=?');$s->execute([$id]); }
}
