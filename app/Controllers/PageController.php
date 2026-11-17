<?php
namespace App\Controllers;
final class PageController extends BaseController
{
    public function about(): void { view('pages/about',['pageTitle'=>'About Peridona']); }
    public function process(): void { view('pages/process',['pageTitle'=>'Our Process']); }
    public function contact(): void { view('pages/contact',['pageTitle'=>'Contact Us']); }
}
