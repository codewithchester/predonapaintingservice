<div class="admin-welcome">
  <div><span class="admin-eyebrow">YOUR WORKSPACE</span><h2>Good to have you here.</h2><p>Here is your current overview of the Peridona website and the workspaces available to your role.</p></div>
  <?php if (\App\Core\Auth::can('projects.manage')): ?><a class="admin-button" href="<?= e(url('/admin/projects/create')) ?>">Add a Project <span>＋</span></a><?php endif; ?>
</div>
<div class="stats-grid">
  <?php if ($canViewEnquiries): ?><div class="stat-card"><span>TOTAL ENQUIRIES</span><strong><?= number_format($stats['enquiries']) ?></strong><small>All customer project requests</small><i>⌁</i></div><div class="stat-card"><span>PENDING ENQUIRIES</span><strong><?= number_format($stats['pending']) ?></strong><small>Awaiting a response</small><i>◷</i></div><?php endif; ?>
  <?php if (\App\Core\Auth::can('projects.manage')): ?><div class="stat-card"><span>PROJECTS</span><strong><?= number_format($stats['projects']) ?></strong><small>Portfolio records</small><i>▧</i></div><?php endif; ?>
  <?php if (\App\Core\Auth::can('services.manage')): ?><div class="stat-card"><span>SERVICES</span><strong><?= number_format($stats['services']) ?></strong><small>Service records</small><i>✳</i></div><?php endif; ?>
</div>
<?php if ($canViewEnquiries): ?>
<section class="admin-panel"><div class="panel-heading"><div><span class="admin-eyebrow">RECENT ACTIVITY</span><h2>Latest enquiries</h2></div><a href="<?= e(url('/admin/enquiries')) ?>" class="admin-text-link">View all →</a></div>
  <?php if ($recent): ?><div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>REFERENCE</th><th>CLIENT</th><th>SERVICE</th><th>STATUS</th><th>RECEIVED</th><th></th></tr></thead><tbody><?php foreach($recent as $item): ?><tr><td><strong><?= e($item['reference_code']) ?></strong></td><td><?= e($item['full_name']) ?></td><td><?= e($item['service_type']?:'Not specified') ?></td><td><span class="status status-<?= e($item['status']) ?>"><i></i><?= e(str_replace('_',' ',$item['status'])) ?></span></td><td><?= e(format_date($item['created_at'])) ?></td><td><a class="table-action" href="<?= e(url('/admin/enquiries/'.$item['id'])) ?>">Open ↗</a></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><div class="admin-empty"><span>◷</span><h3>No enquiries yet</h3><p>New quote requests and customer enquiries will appear here.</p></div><?php endif; ?>
</section>
<?php else: ?>
<section class="admin-panel"><div class="panel-heading"><div><span class="admin-eyebrow">YOUR ACCESS</span><h2>Workspace permissions</h2></div><span class="admin-count-pill">Role based</span></div><div class="admin-empty"><span>◈</span><h3>Your content workspace is ready</h3><p>Use the navigation to manage the website areas assigned to your administrator role. Customer enquiries and team administration remain restricted.</p></div></section>
<?php endif; ?>
<div class="admin-quick-links">
  <?php if (\App\Core\Auth::can('projects.manage')): ?><a href="<?= e(url('/admin/projects')) ?>"><span>▧</span><div><strong>Manage projects</strong><small>Publish new portfolio transformations</small></div><b>→</b></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('services.manage')): ?><a href="<?= e(url('/admin/services')) ?>"><span>✳</span><div><strong>Manage services</strong><small>Keep your service offering current</small></div><b>→</b></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('enquiries.manage')): ?><a href="<?= e(url('/admin/enquiries')) ?>"><span>⌁</span><div><strong>Manage enquiries</strong><small>Review customer project requests</small></div><b>→</b></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('settings.manage')): ?><a href="<?= e(url('/admin/settings')) ?>"><span>⚙</span><div><strong>Website settings</strong><small>Update business contact details</small></div><b>→</b></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('administrators.manage')): ?><a href="<?= e(url('/admin/administrators')) ?>"><span>♙</span><div><strong>Manage administrators</strong><small>Invite trusted team members</small></div><b>→</b></a><?php endif; ?>
</div>
