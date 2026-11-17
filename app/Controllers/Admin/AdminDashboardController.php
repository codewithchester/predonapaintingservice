<?php
namespace App\Controllers\Admin;
use App\Core\Database;
final class AdminDashboardController extends AdminController
{
    public function index(): void
    {
        $this->guard('admin.access');$db=Database::connection();
        $stats=['enquiries'=>(int)$db->query('SELECT COUNT(*) FROM enquiries')->fetchColumn(),'pending'=>(int)$db->query("SELECT COUNT(*) FROM enquiries WHERE status='pending'")->fetchColumn(),'projects'=>(int)$db->query('SELECT COUNT(*) FROM projects')->fetchColumn(),'services'=>(int)$db->query('SELECT COUNT(*) FROM services')->fetchColumn()];
        $canViewEnquiries=\App\Core\Auth::can('enquiries.manage');
        $recent=$canViewEnquiries ? $db->query('SELECT id,reference_code,full_name,service_type,status,created_at FROM enquiries ORDER BY created_at DESC LIMIT 8')->fetchAll() : [];
        $this->adminView('dashboard',compact('stats','recent','canViewEnquiries')+['pageTitle'=>'Dashboard']);
    }
}
