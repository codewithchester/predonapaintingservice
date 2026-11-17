<?php
namespace App\Controllers\Admin;
use App\Models\Setting;
final class AdminSettingsController extends AdminController
{
    public function index(): void { $this->guard('settings.manage');$settings=Setting::all();$map=[];foreach($settings as $s)$map[$s['setting_key']]=$s['setting_value'];$this->adminView('settings/index',compact('map')+['pageTitle'=>'Website Settings']); }
    public function save(): void { $this->guard('settings.manage');$this->verifyPost();$allowed=['company_email','business_location','whatsapp_number','phone_primary','phone_secondary','instagram_url','facebook_url','working_hours'];foreach($allowed as $key)Setting::set($key,trim((string)($_POST[$key]??'')));$this->log('settings.update','Website contact settings updated.');flash('success','Settings saved.');redirect('/admin/settings'); }
}
