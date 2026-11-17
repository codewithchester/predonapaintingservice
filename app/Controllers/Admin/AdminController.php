<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Session;
use App\Middleware\AdminAuth;
use App\Models\ActivityLog;
abstract class AdminController extends BaseController
{
    protected function guard(string $permission = 'admin.access'): array { AdminAuth::handle(); if (!Auth::can($permission)) { http_response_code(403); exit('You do not have permission to access this section.'); } return Auth::user() ?? []; }
    protected function verifyPost(): void { if(!CSRF::verify($_POST['_token']??null)){http_response_code(419);exit('Your session expired. Return to the previous page and try again.');} }
    protected function log(string $action,string $details=''): void { try { ActivityLog::record((int)(Auth::user()['id']??0),$action,$details); } catch(\Throwable $e){error_log($e->getMessage());} }
    protected function adminView(string $template,array $data=[]): void { view('admin/'.$template,$data+['pageTitle'=>'Administrator'], 'admin'); }
}
