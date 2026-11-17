<header class="site-header" id="site-header">
  <div class="topbar"><div class="container topbar-inner"><span>Professional painting & finishing solutions</span><div><a href="tel:<?= e(preg_replace('/\s+/', '', setting('phone_primary','0547320593'))) ?>"><?= e(setting('phone_primary','0547320593')) ?></a><span class="topbar-divider">·</span><a href="tel:<?= e(preg_replace('/\s+/', '', setting('phone_secondary','0596262939'))) ?>"><?= e(setting('phone_secondary','0596262939')) ?></a></div></div></div>
  <nav class="navbar container" aria-label="Main navigation">
    <a class="brand" href="<?= e(url('/')) ?>"><img src="<?= e(asset('images/logo.svg')) ?>" alt="Peridona Painting Service"></a>
    <button class="mobile-menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false"><span></span><span></span><span></span></button>
    <div class="nav-panel">
      <div class="nav-links">
        <a href="<?= e(url('/')) ?>">Home</a><a href="<?= e(url('/about')) ?>">About</a><a href="<?= e(url('/services')) ?>">Services</a><a href="<?= e(url('/projects')) ?>">Projects</a><a href="<?= e(url('/process')) ?>">Our Process</a><a href="<?= e(url('/contact')) ?>">Contact</a>
      </div>
      <div class="nav-actions"><button class="theme-toggle" type="button" aria-label="Toggle dark mode" title="Toggle theme"><span class="theme-icon">☾</span></button><a class="button button-primary button-small" href="<?= e(url('/quote')) ?>">Request a Quote <span>↗</span></a></div>
    </div>
  </nav>
</header>
