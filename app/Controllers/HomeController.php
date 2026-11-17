<?php
namespace App\Controllers;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
final class HomeController extends BaseController
{
    public function index(): void
    {
        $services = $projects = $testimonials = [];
        try { $services = array_slice(Service::all(), 0, 6); $projects = array_slice(Project::all(), 0, 6); $testimonials = array_slice(Testimonial::all(), 0, 6); }
        catch (\Throwable $e) { error_log('[Peridona home] '.$e->getMessage()); }
        view('pages/home', compact('services','projects','testimonials') + ['pageTitle'=>'Professional Painting & Finishing in Ghana']);
    }
}
