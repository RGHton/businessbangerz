<?php if (!defined('AFF_BASE')) { http_response_code(403); exit('Direct access denied.'); } ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e(SITE_NAME) ?> — <?= e(SITE_TAG) ?></title>
<meta name="description" content="<?= e(SITE_DESC) ?>">
<link rel="canonical" href="<?= e(SITE_URL) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e(SITE_NAME) ?> — <?= e(SITE_TAG) ?>">
<meta property="og:description" content="<?= e(SITE_DESC) ?>">
<meta property="og:url" content="<?= e(SITE_URL) ?>">
<?php if (is_file(__DIR__ . '/../' . OG_IMAGE)): ?>
<meta property="og:image" content="<?= e(rtrim(SITE_URL, '/') . '/' . OG_IMAGE) ?>">
<meta name="twitter:card" content="summary_large_image">
<?php endif; ?>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E🔥%3C/text%3E%3C/svg%3E">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Anton&amp;family=Space+Grotesk:wght@400;500;700&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(v('assets/css/style.css')) ?>">
</head>
<body>
<a class="skip" href="#main">Skip to content</a>

<header class="nav" id="nav">
  <div class="wrap nav-inner">
    <a class="brand" href="#top">
      <span class="brand-mark" aria-hidden="true">🔥</span>
      <span class="brand-text"><?= e(SITE_NAME) ?></span>
    </a>
    <nav class="nav-links" aria-label="Sections">
      <a href="#player">The commercial</a>
      <a href="#why">Why it works</a>
      <a href="#uses">Use cases</a>
      <a href="#faq">FAQ</a>
    </nav>
    <a class="btn btn-sm btn-pink" href="<?= e(aff()) ?>" target="_blank" rel="noopener sponsored">🎵 Book a Jam Sesh</a>
  </div>
</header>
