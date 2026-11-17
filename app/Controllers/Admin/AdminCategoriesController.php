<?php
namespace App\Controllers\Admin;
use App\Models\Category;
final class AdminCategoriesController extends AdminController
{
    public function index(): void { $this->guard('categories.manage');$categories=Category::all();$this->adminView('categories/index',compact('categories')+['pageTitle'=>'Project Categories']); }
    public function create(): void { $this->guard('categories.manage');$this->verifyPost();$name=trim((string)($_POST['name']??''));if($name!==''){try{Category::create($name);flash('success','Category added.');}catch(\Throwable $e){flash('error','Could not add category. It may already exist.');}}redirect('/admin/categories'); }
    public function delete(string $id): void { $this->guard('categories.manage');$this->verifyPost();Category::delete((int)$id);flash('success','Category removed.');redirect('/admin/categories'); }
}
