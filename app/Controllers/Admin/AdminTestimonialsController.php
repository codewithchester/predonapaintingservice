<?php
namespace App\Controllers\Admin;
use App\Models\Testimonial;
final class AdminTestimonialsController extends AdminController
{
    public function index(): void { $this->guard('testimonials.manage');$testimonials=Testimonial::all(false);$this->adminView('testimonials/index',compact('testimonials')+['pageTitle'=>'Testimonials']); }
    public function create(): void { $this->guard('testimonials.manage');$testimonial=null;$this->adminView('testimonials/form',compact('testimonial')+['pageTitle'=>'Add Testimonial']); }
    public function edit(string $id): void { $this->guard('testimonials.manage');$testimonial=null;foreach(Testimonial::all(false) as $t)if((int)$t['id']===(int)$id)$testimonial=$t;if(!$testimonial){flash('error','Testimonial not found.');redirect('/admin/testimonials');}$this->adminView('testimonials/form',compact('testimonial')+['pageTitle'=>'Edit Testimonial']); }
    public function save(): void { $this->guard('testimonials.manage');$this->verifyPost();$id=(int)($_POST['id']??0);$data=['customer_name'=>trim((string)($_POST['customer_name']??'')),'role_label'=>trim((string)($_POST['role_label']??'')),'quote'=>trim((string)($_POST['quote']??'')),'is_published'=>isset($_POST['is_published'])?1:0];if($data['customer_name']===''||$data['quote']===''){flash('error','Name and testimonial are required.');redirect($id?'/admin/testimonials/'.$id.'/edit':'/admin/testimonials/create');}Testimonial::save($data,$id?:null);flash('success','Testimonial saved.');redirect('/admin/testimonials'); }
    public function delete(string $id): void { $this->guard('testimonials.manage');$this->verifyPost();Testimonial::delete((int)$id);flash('success','Testimonial removed.');redirect('/admin/testimonials'); }
}
