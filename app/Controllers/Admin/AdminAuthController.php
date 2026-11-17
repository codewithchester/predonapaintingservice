<?php
namespace App\Controllers\Admin;
use App\Core\Auth;
use App\Core\Session;
final class AdminAuthController extends AdminController
{
    public function login(): void
    {
        try { if(Auth::check()) redirect('/admin'); } catch (\Throwable $e) { error_log('[Admin auth] '.$e->getMessage()); flash('error','Administrator sign-in is unavailable until the database is configured. Follow the installation steps in README.md.'); }
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $this->verifyPost(); Session::start();
            $attempts=(int)($_SESSION['login_attempts']??0);$last=(int)($_SESSION['login_last']??0);
            if($attempts>=5 && time()-$last<900){flash('error','Too many attempts. Please wait 15 minutes before trying again.');redirect('/admin/login');}
            $email=trim((string)($_POST['email']??''));$password=(string)($_POST['password']??'');
            if(Auth::attempt($email,$password)){unset($_SESSION['login_attempts'],$_SESSION['login_last']);$this->log('admin.login','Administrator signed in.');redirect('/admin');}
            $_SESSION['login_attempts']=$attempts+1;$_SESSION['login_last']=time();flash('error','The email or password is incorrect.');redirect('/admin/login');
        }
        view('admin/login',['pageTitle'=>'Administrator Sign In'],'auth');
    }
    public function logout(): void { $this->verifyPost();$this->log('admin.logout','Administrator signed out.');Auth::logout();redirect('/admin/login'); }
}
