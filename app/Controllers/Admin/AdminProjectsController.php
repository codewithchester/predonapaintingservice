<?php
namespace App\Controllers\Admin;
use App\Core\Database;
use App\Core\Uploader;
use App\Models\Project;
use App\Models\Category;
final class AdminProjectsController extends AdminController
{
    public function index(): void { $this->guard('projects.manage');$projects=Project::all(false);$this->adminView('projects/index',compact('projects')+['pageTitle'=>'Projects']); }
    public function create(): void { $this->guard('projects.manage');$project=null;$categories=Category::all();$this->adminView('projects/form',compact('project','categories')+['pageTitle'=>'Add Project']); }
    public function edit(string $id): void { $this->guard('projects.manage');$project=Project::find((int)$id);if(!$project){flash('error','Project not found.');redirect('/admin/projects');}$categories=Category::all();$this->adminView('projects/form',compact('project','categories')+['pageTitle'=>'Edit Project']); }
    public function save(): void
    {
        $this->guard('projects.manage');$this->verifyPost();$id=(int)($_POST['id']??0);$existing=$id?Project::find($id):null;
        try {
            $image=Uploader::image($_FILES['image']??[],'projects')??($existing['image_path']??null);
            $before=Uploader::image($_FILES['before_image']??[],'projects')??($existing['before_image']??null);
            $after=Uploader::image($_FILES['after_image']??[],'projects')??($existing['after_image']??null);
            $title=trim((string)($_POST['title']??''));if($title==='')throw new \RuntimeException('Project title is required.');
            $data=['category_id'=>(int)($_POST['category_id']??0),'title'=>$title,'slug'=>\slugify(trim((string)($_POST['slug']??''))?:$title),'location'=>trim((string)($_POST['location']??'')),'summary'=>trim((string)($_POST['summary']??'')),'description'=>trim((string)($_POST['description']??'')),'image_path'=>$image,'before_image'=>$before,'after_image'=>$after,'is_featured'=>isset($_POST['is_featured'])?1:0,'is_published'=>isset($_POST['is_published'])?1:0];
            Project::save($data,$id?:null);$this->log('project.save',$title);flash('success','Project saved successfully.');redirect('/admin/projects');
        }catch(\Throwable $e){error_log('[Admin project] '.$e->getMessage());flash('error',$e->getMessage());redirect($id?'/admin/projects/'.$id.'/edit':'/admin/projects/create');}
    }
    public function delete(string $id): void { $this->guard('projects.manage');$this->verifyPost();$project=Project::find((int)$id);if($project){foreach(['image_path','before_image','after_image'] as $field)Uploader::delete($project[$field]??null);Project::delete((int)$id);$this->log('project.delete',$project['title']);flash('success','Project deleted.');}redirect('/admin/projects'); }
}
