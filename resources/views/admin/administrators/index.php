<div class="admin-page-heading">
  <div><span class="admin-eyebrow">TEAM ACCESS & SECURITY</span><p>Create staff accounts, assign access levels and manage administrator status.</p></div>
  <a class="admin-button" href="<?= e(url('/admin/administrators/create')) ?>"><span class="button-plus">＋</span> Add Administrator</a>
</div>
<div class="admin-info-banner"><span class="admin-info-icon">✦</span><div><strong>Keep access intentional.</strong><p>Only give each person the permissions needed for their work. Super Administrators can manage all website areas and other administrator accounts.</p></div></div>
<section class="admin-panel admin-users-panel">
  <div class="panel-heading"><div><span class="admin-eyebrow">AUTHORIZED USERS</span><h2>Administrator accounts</h2></div><span class="admin-count-pill"><?= count($administrators) ?> total</span></div>
  <?php if ($administrators): ?>
  <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>TEAM MEMBER</th><th>ROLE</th><th>STATUS</th><th>LAST ACTIVE</th><th>ADDED</th><th>ACTIONS</th></tr></thead><tbody>
  <?php foreach ($administrators as $admin): $initial = mb_strtoupper(mb_substr($admin['name'], 0, 1)); ?>
    <tr>
      <td><div class="admin-person-cell"><span class="admin-person-avatar"><?= e($initial) ?></span><span><strong><?= e($admin['name']) ?></strong><small><?= e($admin['email']) ?></small></span></div></td>
      <td><span class="role-pill <?= ($admin['role_name']??'')==='Super Administrator'?'role-super':'' ?>"><?= e($admin['role_name'] ?: 'Unassigned') ?></span></td>
      <td><span class="status <?= (int)$admin['is_active']===1?'status-completed':'status-closed' ?>"><i></i><?= (int)$admin['is_active']===1?'Active':'Inactive' ?></span></td>
      <td><?= $admin['last_login_at'] ? e(format_date($admin['last_login_at'])) : 'Never' ?></td>
      <td><?= e(format_date($admin['created_at'])) ?></td>
      <td><div class="actions-cell"><a class="table-action" href="<?= e(url('/admin/administrators/'.$admin['id'].'/edit')) ?>">Edit</a>
      <?php if ((int)$admin['id'] !== (int)(\App\Core\Auth::user()['id']??0)): ?>
      <form method="post" action="<?= e(url('/admin/administrators/'.$admin['id'].'/toggle')) ?>" data-confirm="<?= (int)$admin['is_active']===1?'Deactivate':'Activate' ?> this administrator account?"> <?= csrf_field() ?><button class="table-action <?= (int)$admin['is_active']===1?'table-danger':'table-enable' ?>" type="submit"><?= (int)$admin['is_active']===1?'Deactivate':'Activate' ?></button></form>
      <?php else: ?><span class="self-account-note">You</span><?php endif; ?></div></td>
    </tr>
  <?php endforeach; ?>
  </tbody></table></div>
  <?php else: ?><div class="admin-empty"><span>♙</span><h3>No administrator accounts found</h3><p>Create an account to give a trusted team member access.</p></div><?php endif; ?>
</section>
<div class="admin-role-grid">
  <article class="admin-role-card"><span class="admin-role-icon">♛</span><div><h3>Super Administrator</h3><p>Full website and team access, including administrator accounts and permissions.</p></div><span class="admin-role-tag">Full access</span></article>
  <article class="admin-role-card"><span class="admin-role-icon">✎</span><div><h3>Content Manager</h3><p>Manage projects, services, categories and approved testimonials.</p></div><span class="admin-role-tag">Content</span></article>
  <article class="admin-role-card"><span class="admin-role-icon">◎</span><div><h3>Enquiry Manager</h3><p>View and follow up on quotation enquiries and contact messages.</p></div><span class="admin-role-tag">Customer care</span></article>
</div>
