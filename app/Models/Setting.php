<?php
namespace App\Models;
use App\Core\Database;
final class Setting
{
    public static function all(): array { return Database::connection()->query('SELECT * FROM website_settings ORDER BY setting_key')->fetchAll(); }
    public static function set(string $key,string $value): void { $s=Database::connection()->prepare('INSERT INTO website_settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');$s->execute([$key,$value]); }
}
