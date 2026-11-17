<?php
namespace App\Controllers;
use App\Core\Uploader;
use App\Core\Validator;
use App\Models\Enquiry;
final class QuoteController extends BaseController
{
    public function index(): void { view('pages/quote',['pageTitle'=>'Request a Painting Quote']); }
    public function store(): void
    {
        $this->requireCsrf();
        if (!empty($_POST['website'] ?? '')) redirect('/quote');
        $data=['full_name'=>$this->validateText($_POST['full_name']??'',120),'phone'=>$this->validateText($_POST['phone']??'',30),'email'=>$this->validateText($_POST['email']??'',190),'location'=>$this->validateText($_POST['location']??'',180),'property_type'=>$this->validateText($_POST['property_type']??'',100),'service_type'=>$this->validateText($_POST['service_type']??'',120),'project_size'=>$this->validateText($_POST['project_size']??'',100),'preferred_date'=>$this->validateText($_POST['preferred_date']??'',20),'message'=>$this->validateText($_POST['message']??'',4000)];
        $errors=Validator::required($data,['full_name'=>'Your name','phone'=>'Phone number','message'=>'Project description']);
        if($data['phone']!==''&&!Validator::phone($data['phone']))$errors['phone']='Enter a valid phone number.';
        if($data['email']!==''&&!Validator::email($data['email']))$errors['email']='Enter a valid email address.';
        if($data['preferred_date']!==''&&!preg_match('/^\d{4}-\d{2}-\d{2}$/',$data['preferred_date']))$errors['preferred_date']='Select a valid date.';
        if($errors){$_SESSION['_old']=$data;flash('error',reset($errors));redirect('/quote');}
        try {
            $attachment=Uploader::image($_FILES['attachment']??[],'private-enquiries');
            $reference='PER-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
            $data += ['reference_code'=>$reference,'attachment_path'=>$attachment];
            Enquiry::create($data); $_SESSION['quote_reference']=$reference;redirect('/quote/success');
        } catch(\Throwable $e){error_log('[Quote] '.$e->getMessage());$_SESSION['_old']=$data;flash('error',$e->getMessage());redirect('/quote');}
    }
    public function success(): void { $reference=$_SESSION['quote_reference']??null;unset($_SESSION['quote_reference']);view('pages/quote-success',compact('reference')+['pageTitle'=>'Enquiry Received']); }
}
