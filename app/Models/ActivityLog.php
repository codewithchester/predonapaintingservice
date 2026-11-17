<?php
namespace App\Models;
use App\Core\Database;
final class ActivityLog
{
    public static function record(?int $adminId,string $action,string $details=''): void { $s=Database::connection()->prepare('INSERT INTO activity_logs(administrator_id,action,details,ip_address) VALUES(?,?,?,?)');$s->execute([$adminId,$action,$details,$_SERVER['REMOTE_ADDR']??null]); }
}
