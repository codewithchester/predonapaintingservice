<?php
namespace App\Controllers;
use App\Models\Project;
use App\Models\Category;
final class ProjectController extends BaseController
{
    public function index(): void { try { $projects=Project::all();$categories=Category::all(); } catch (\Throwable $e) { error_log('[Peridona projects] '.$e->getMessage()); $projects=[];$categories=[]; } view('pages/projects',compact('projects','categories')+['pageTitle'=>'Our Projects']); }
    public function show(string $slug): void { try { $project=Project::bySlug($slug); } catch (\Throwable $e) { error_log('[Peridona project detail] '.$e->getMessage()); $project=null; }if(!$project){http_response_code(404);view('pages/404');return;}view('pages/project-detail',compact('project')+['pageTitle'=>$project['title']]); }
}
