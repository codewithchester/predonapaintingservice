<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#800d19">
  <title><?= e(($pageTitle ?? 'Admin') . ' · Peridona Control Centre') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="admin-body">
<?php $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'; ?>
<aside class="admin-sidebar">
  <a class="admin-brand" href="<?= e(url('/admin')) ?>"><img src="<?= e(asset('images/logo.svg')) ?>" alt="Peridona Painting Service"><span>PAINTING SERVICE <i>•</i> CONTROL CENTRE</span></a>
  <div class="admin-nav-label">WORKSPACE</div>
  <a href="<?= e(url('/admin')) ?>" class="<?= rtrim($currentPath,'/')===rtrim(url('/admin'),'/')?'active':'' ?>"><span class="nav-icon">◫</span><span>Overview</span></a>
  <?php if (\App\Core\Auth::can('enquiries.manage')): ?><a href="<?= e(url('/admin/enquiries')) ?>" class="<?= str_contains($currentPath,'/admin/enquiries')?'active':'' ?>"><span class="nav-icon">⌁</span><span>Enquiries</span></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('messages.manage')): ?><a href="<?= e(url('/admin/messages')) ?>" class="<?= str_contains($currentPath,'/admin/messages')?'active':'' ?>"><span class="nav-icon">✉</span><span>Contact messages</span></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('projects.manage')): ?><a href="<?= e(url('/admin/projects')) ?>" class="<?= str_contains($currentPath,'/admin/projects')?'active':'' ?>"><span class="nav-icon">▧</span><span>Projects</span></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('services.manage')): ?><a href="<?= e(url('/admin/services')) ?>" class="<?= str_contains($currentPath,'/admin/services')?'active':'' ?>"><span class="nav-icon">✳</span><span>Services</span></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('categories.manage')): ?><a href="<?= e(url('/admin/categories')) ?>" class="<?= str_contains($currentPath,'/admin/categories')?'active':'' ?>"><span class="nav-icon">▤</span><span>Categories</span></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('testimonials.manage')): ?><a href="<?= e(url('/admin/testimonials')) ?>" class="<?= str_contains($currentPath,'/admin/testimonials')?'active':'' ?>"><span class="nav-icon">❝</span><span>Testimonials</span></a><?php endif; ?>
  <div class="admin-nav-label">CONFIGURATION</div>
  <?php if (\App\Core\Auth::can('settings.manage')): ?><a href="<?= e(url('/admin/settings')) ?>" class="<?= str_contains($currentPath,'/admin/settings')?'active':'' ?>"><span class="nav-icon">⚙</span><span>Website settings</span></a><?php endif; ?>
  <?php if (\App\Core\Auth::can('administrators.manage')): ?><a href="<?= e(url('/admin/administrators')) ?>" class="<?= str_contains($currentPath,'/admin/administrators')?'active':'' ?>"><span class="nav-icon">♙</span><span>Administrators</span><span class="nav-new">TEAM</span></a><?php endif; ?>
  <a href="<?= e(url('/')) ?>" target="_blank" rel="noopener"><span class="nav-icon">↗</span><span>View website</span></a>
  <div class="sidebar-bottom"><div class="sidebar-secure"><span>●</span><div><strong>Secure workspace</strong><small>Administrator session active</small></div></div><form method="post" action="<?= e(url('/admin/logout')) ?>" class="admin-logout"><?= csrf_field() ?><button type="submit"><span class="nav-icon">⇥</span><span>Sign out</span></button></form></div>
</aside>
<div class="admin-main">
  <header class="admin-topbar"><div class="topbar-title"><span class="admin-top-kicker">PERIDONA PAINTING SERVICE</span><h1><?= e($pageTitle ?? 'Dashboard') ?></h1></div><div class="admin-top-actions"><span class="admin-live-status"><i></i> SYSTEM ONLINE</span><div class="admin-user"><span class="admin-avatar"><?= e(mb_strtoupper(mb_substr((string)(\App\Core\Auth::user()['name'] ?? 'P'),0,1))) ?></span><span><strong><?= e(\App\Core\Auth::user()['name'] ?? 'Administrator') ?></strong><small><?= e(\App\Core\Auth::user()['role_name'] ?? 'Team member') ?></small></span></div></div></header>
  <main class="admin-content">
    <?php if ($message = flash('success')): ?><div class="admin-alert success"><span>✓</span><?= e($message) ?></div><?php endif; ?>
    <?php if ($message = flash('error')): ?><div class="admin-alert error"><span>!</span><?= e($message) ?></div><?php endif; ?>
    <?= $content ?>
  </main>
  <footer class="admin-footer"><span>© <?= date('Y') ?> Peridona Painting Service</span><span>EXCELLENCE IN EVERY FINISH <i>•</i> SECURE ADMINISTRATION</span></footer>
</div>
<script src="<?= e(asset('js/admin.js')) ?>" defer></script>
</body></html>
