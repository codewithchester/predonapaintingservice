<?php
namespace App\Controllers;
use App\Core\Validator;
use App\Models\ContactMessage;
final class ContactController extends BaseController
{
    public function store(): void
    {
        $this->requireCsrf();
        if (!empty($_POST['website'] ?? '')) { redirect('/contact'); }
        $data=['full_name'=>$this->validateText($_POST['full_name']??'',120),'email'=>$this->validateText($_POST['email']??'',190),'phone'=>$this->validateText($_POST['phone']??'',30),'subject'=>$this->validateText($_POST['subject']??'',160),'message'=>$this->validateText($_POST['message']??'',4000)];
        $errors=Validator::required($data,['full_name'=>'Your name','email'=>'Email address','message'=>'Message']);
        if($data['email']!==''&&!Validator::email($data['email']))$errors['email']='Enter a valid email address.';
        if($data['phone']!==''&&!Validator::phone($data['phone']))$errors['phone']='Enter a valid phone number.';
        if($errors){$_SESSION['_old']=$data;flash('error',reset($errors));redirect('/contact');}
        try { ContactMessage::create($data); flash('success','Thank you. Your message has been sent to the Peridona team.'); }
        catch(\Throwable $e){error_log('[Contact] '.$e->getMessage());flash('error','We could not send your message right now. Please contact us on WhatsApp.');}
        redirect('/contact');
    }
}
