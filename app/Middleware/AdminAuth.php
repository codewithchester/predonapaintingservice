<?php
namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

final class AdminAuth
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            Session::flash('error', 'Please sign in to access the administrator area.');
            redirect('/admin/login');
        }
    }
}
