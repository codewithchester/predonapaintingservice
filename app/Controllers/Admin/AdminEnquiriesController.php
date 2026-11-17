<?php
namespace App\Controllers\Admin;
use App\Models\Enquiry;
final class AdminEnquiriesController extends AdminController
{
    public function index(): void { $this->guard('enquiries.manage');$enquiries=Enquiry::all();$this->adminView('enquiries/index',compact('enquiries')+['pageTitle'=>'Customer Enquiries']); }
    public function show(string $id): void { $this->guard('enquiries.manage');$enquiry=Enquiry::find((int)$id);if(!$enquiry){flash('error','Enquiry not found.');redirect('/admin/enquiries');}$this->adminView('enquiries/show',compact('enquiry')+['pageTitle'=>'Enquiry '.$enquiry['reference_code']]); }
    public function update(string $id): void { $this->guard('enquiries.manage');$this->verifyPost();$status=(string)($_POST['status']??'pending');if(!in_array($status,['pending','contacted','quoted','in_progress','completed','closed'],true))$status='pending';Enquiry::updateStatus((int)$id,$status);$this->log('enquiry.status','Enquiry '.$id.' → '.$status);flash('success','Enquiry status updated.');redirect('/admin/enquiries/'.$id); }
}
