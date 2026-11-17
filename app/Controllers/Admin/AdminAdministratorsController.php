<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Models\Admin;

final class AdminAdministratorsController extends AdminController
{
    public function index(): void
    {
        $this->guard('administrators.manage');
        $administrators = Admin::allForManagement();
        $this->adminView('administrators/index', compact('administrators') + ['pageTitle' => 'Administrator Management']);
    }

    public function create(): void
    {
        $this->guard('administrators.manage');
        $administrator = null;
        $roles = Admin::roles();
        $this->adminView('administrators/form', compact('administrator', 'roles') + ['pageTitle' => 'Add Administrator']);
    }

    public function edit(string $id): void
    {
        $this->guard('administrators.manage');
        $administrator = Admin::managementRecord((int)$id);
        if (!$administrator) { flash('error', 'Administrator not found.'); redirect('/admin/administrators'); }
        $roles = Admin::roles();
        $this->adminView('administrators/form', compact('administrator', 'roles') + ['pageTitle' => 'Edit Administrator']);
    }

    public function save(): void
    {
        $actor = $this->guard('administrators.manage');
        $this->verifyPost();
        $id = (int)($_POST['id'] ?? 0);
        $existing = $id > 0 ? Admin::managementRecord($id) : null;
        if ($id > 0 && !$existing) { flash('error', 'Administrator not found.'); redirect('/admin/administrators'); }

        $name = trim((string)($_POST['name'] ?? ''));
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $roleId = (int)($_POST['role_id'] ?? 0);
        $password = (string)($_POST['password'] ?? '');
        $active = isset($_POST['is_active']) ? 1 : 0;

        try {
            if ($name === '' || mb_strlen($name) > 120) throw new \RuntimeException('Enter a name of 1 to 120 characters.');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) throw new \RuntimeException('Enter a valid email address.');
            if (!Admin::roleExists($roleId)) throw new \RuntimeException('Select a valid administrator role.');
            if (Admin::emailExists($email, $id ?: null)) throw new \RuntimeException('That email address is already assigned to an administrator.');
            if (!$existing && strlen($password) < 12) throw new \RuntimeException('A new administrator password must contain at least 12 characters.');
            if ($password !== '' && strlen($password) < 12) throw new \RuntimeException('A replacement password must contain at least 12 characters.');

            $newRoleName = Admin::roleName($roleId);
            $wasSuper = ($existing['role_name'] ?? '') === 'Super Administrator' && (int)$existing['is_active'] === 1;
            $willRemainSuper = $newRoleName === 'Super Administrator' && $active === 1;
            if ($wasSuper && !$willRemainSuper && Admin::activeSuperAdminCount($id) === 0) {
                throw new \RuntimeException('You cannot remove or deactivate the last active Super Administrator. Promote another administrator first.');
            }
            if ($existing && (int)$actor['id'] === $id && $active === 0) {
                throw new \RuntimeException('You cannot deactivate your own account while signed in.');
            }
            if ($existing && (int)$actor['id'] === $id && !$willRemainSuper) {
                throw new \RuntimeException('You cannot remove your own Super Administrator access while signed in.');
            }

            $data = [
                'role_id' => $roleId,
                'name' => $name,
                'email' => $email,
                'is_active' => $active,
                'password_hash' => $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null,
            ];
            if ($existing) {
                Admin::update($id, $data);
                $this->log('administrator.update', 'Updated administrator #' . $id . ' (' . $email . ').');
                flash('success', 'Administrator updated successfully.');
            } else {
                $newId = Admin::create($data);
                $this->log('administrator.create', 'Created administrator #' . $newId . ' (' . $email . ').');
                flash('success', 'Administrator account created successfully.');
            }
            redirect('/admin/administrators');
        } catch (\RuntimeException $e) {
            flash('error', $e->getMessage());
            redirect($existing ? '/admin/administrators/' . $id . '/edit' : '/admin/administrators/create');
        } catch (\Throwable $e) {
            error_log('[Peridona administrator management] ' . $e->getMessage());
            flash('error', 'The administrator account could not be saved. Check the database connection and try again.');
            redirect($existing ? '/admin/administrators/' . $id . '/edit' : '/admin/administrators/create');
        }
    }

    public function toggle(string $id): void
    {
        $actor = $this->guard('administrators.manage');
        $this->verifyPost();
        $id = (int)$id;
        $target = Admin::managementRecord($id);
        if (!$target) { flash('error', 'Administrator not found.'); redirect('/admin/administrators'); }
        if ((int)$actor['id'] === $id) { flash('error', 'You cannot deactivate your own account.'); redirect('/admin/administrators'); }

        $activate = (int)$target['is_active'] !== 1;
        if (!$activate && ($target['role_name'] ?? '') === 'Super Administrator' && Admin::activeSuperAdminCount($id) === 0) {
            flash('error', 'You cannot deactivate the last active Super Administrator.');
            redirect('/admin/administrators');
        }
        Admin::updateStatus($id, $activate);
        $this->log($activate ? 'administrator.activate' : 'administrator.deactivate', 'Administrator #' . $id . ' status changed.');
        flash('success', $activate ? 'Administrator activated.' : 'Administrator deactivated.');
        redirect('/admin/administrators');
    }
}
