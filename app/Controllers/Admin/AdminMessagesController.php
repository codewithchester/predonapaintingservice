<?php
namespace App\Controllers\Admin;
use App\Models\ContactMessage;
final class AdminMessagesController extends AdminController
{
    public function index(): void { $this->guard('messages.manage');$messages=ContactMessage::all();$this->adminView('messages/index',compact('messages')+['pageTitle'=>'Contact Messages']); }
    public function show(string $id): void { $this->guard('messages.manage');$message=ContactMessage::find((int)$id);if(!$message){flash('error','Message not found.');redirect('/admin/messages');}ContactMessage::markRead((int)$id);$this->adminView('messages/show',compact('message')+['pageTitle'=>'Contact Message']); }
}
