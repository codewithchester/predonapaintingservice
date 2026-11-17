<!doctype html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#800d19">
  <title><?= e(($pageTitle ?? 'Professional Painting & Finishing') . ' | Peridona Painting Service') ?></title>
  <meta name="description" content="Professional painting and finishing solutions in Ghana. Peridona Painting Service brings spaces to life with thoughtful colour, careful preparation and excellence in every finish.">
  <meta property="og:title" content="<?= e(($pageTitle ?? 'Peridona Painting Service')) ?>">
  <meta property="og:description" content="Excellence in Every Finish. Professional painting and finishing solutions in Ghana.">
  <link rel="canonical" href="<?= e(current_url()) ?>">
  <meta property="og:url" content="<?= e(current_url()) ?>">
  <meta property="og:type" content="website">
  <meta property="og:image" content="<?= e(asset('images/brand/flyer-reference.jpeg')) ?>">
  <link rel="icon" type="image/svg+xml" href="<?= e(asset('images/logo.svg')) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
  <?php $businessSchema = ['@context'=>'https://schema.org','@type'=>'PaintingContractor','name'=>'Peridona Painting Service','description'=>'Professional painting and finishing services in Ghana.','url'=>url('/'),'telephone'=>setting('phone_primary','0547320593'),'areaServed'=>['@type'=>'Country','name'=>'Ghana']]; ?>
  <script type="application/ld+json"><?= json_encode($businessSchema, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
</head>
<body>
<?php require dirname(__DIR__) . '/components/nav.php'; ?>
<main id="main-content"><?= $content ?></main>
<?php require dirname(__DIR__) . '/components/footer.php'; ?>
<a class="whatsapp-float" href="<?= e(whatsapp_url()) ?>" target="_blank" rel="noopener" aria-label="Chat with Peridona on WhatsApp"><span>◉</span></a>
<button class="back-to-top" type="button" aria-label="Back to top">↑</button>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>
