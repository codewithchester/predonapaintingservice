<?php
namespace App\Controllers;
use App\Models\Service;
final class ServiceController extends BaseController
{
    public function index(): void { try { $services = Service::all(); } catch (\Throwable $e) { error_log('[Peridona services] '.$e->getMessage()); $services = []; } view('pages/services',['services'=>$services,'pageTitle'=>'Painting & Finishing Services']); }
    public function show(string $slug): void { try { $service=Service::bySlug($slug); } catch (\Throwable $e) { error_log('[Peridona service detail] '.$e->getMessage()); $service=null; } if(!$service){http_response_code(404);view('pages/404');return;} view('pages/service-detail',compact('service')+['pageTitle'=>$service['title']]); }
}
