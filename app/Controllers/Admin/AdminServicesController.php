<?php
namespace App\Controllers\Admin;
use App\Core\Uploader;
use App\Models\Service;
final class AdminServicesController extends AdminController
{
    public function index(): void { $this->guard('services.manage');$services=Service::all(false);$this->adminView('services/index',compact('services')+['pageTitle'=>'Services']); }
    public function create(): void { $this->guard('services.manage');$service=null;$this->adminView('services/form',compact('service')+['pageTitle'=>'Add Service']); }
    public function edit(string $id): void { $this->guard('services.manage');$service=Service::find((int)$id);if(!$service){flash('error','Service not found.');redirect('/admin/services');}$this->adminView('services/form',compact('service')+['pageTitle'=>'Edit Service']); }
    public function save(): void { $this->guard('services.manage');$this->verifyPost();$id=(int)($_POST['id']??0);$existing=$id?Service::find($id):null;try{$title=trim((string)($_POST['title']??''));if($title==='')throw new \RuntimeException('Service title is required.');$image=Uploader::image($_FILES['image']??[],'services')??($existing['image_path']??null);$data=['title'=>$title,'slug'=>\slugify(trim((string)($_POST['slug']??''))?:$title),'short_description'=>trim((string)($_POST['short_description']??'')),'description'=>trim((string)($_POST['description']??'')),'icon'=>trim((string)($_POST['icon']??'paintbrush')),'image_path'=>$image,'is_published'=>isset($_POST['is_published'])?1:0,'sort_order'=>(int)($_POST['sort_order']??0)];Service::save($data,$id?:null);$this->log('service.save',$title);flash('success','Service saved.');redirect('/admin/services');}catch(\Throwable $e){flash('error',$e->getMessage());redirect($id?'/admin/services/'.$id.'/edit':'/admin/services/create');} }
    public function delete(string $id): void { $this->guard('services.manage');$this->verifyPost();Service::delete((int)$id);$this->log('service.delete','Service ID '.$id);flash('success','Service deleted.');redirect('/admin/services'); }
}
