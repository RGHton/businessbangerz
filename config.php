<?php
/**
 * Everything you are likely to want to change lives in this file.
 * Edit it, upload it, done — no other file needs touching for normal tweaks.
 */

// ---------------------------------------------------------------------------
// 1. YOUR AFFILIATE LINK
// ---------------------------------------------------------------------------
// Base destination. Keep the trailing slash off.
define('AFF_BASE', 'https://businessbangerz.com');

// Your affiliate / referral identifier, and the query key it travels under.
// If Business Bangerz gave you a full link instead (e.g. https://businessbangerz.com/?via=you),
// set AFF_PARAM to 'via' and AFF_ID to 'you'.
define('AFF_PARAM', 'ref');
define('AFF_ID',    'YOUR-AFFILIATE-ID');

// ---------------------------------------------------------------------------
// 2. THE SONG COMMERCIAL
// ---------------------------------------------------------------------------
// Drop your mp3 in assets/audio/ and put the filename here.
define('SONG_FILE',   'assets/audio/buzz-bang-teaser.mp3');
define('SONG_TITLE',  'Buzz Bang Teaser');
define('SONG_ARTIST', 'A 47-second argument, sung');

// ---------------------------------------------------------------------------
// 3. SITE IDENTITY
// ---------------------------------------------------------------------------
define('SITE_NAME',  'Make It A Banger');
define('SITE_TAG',   'An independent fan page for Business Bangerz');
define('SITE_DESC',  'Nobody hums a slide deck. Business Bangerz writes original songs for training, launches and policy rollouts — press play and hear why.');
define('SITE_URL',   'https://your-domain.com');   // used for canonical + social tags
define('OG_IMAGE',   'assets/img/og.png');          // optional; delete the file and it is skipped

// Shown in the footer. The FTC expects this to be plain and easy to find.
define('AFF_DISCLOSURE',
  'This is an independent affiliate site. It is not operated by Business Bangerz or Neu&sup2; Media. '
  . 'Links here are affiliate links, which means a booking or subscription made after clicking one '
  . 'may earn this site a commission — at no extra cost to you.');

/**
 * Build an affiliate URL for any path or anchor on the destination site.
 * aff()            -> https://businessbangerz.com/?ref=YOUR-ID
 * aff('#jukebox')  -> https://businessbangerz.com/#jukebox?... (anchor kept last)
 */
function aff(string $path = ''): string {
    $anchor = '';
    if (strpos($path, '#') !== false) {
        [$path, $anchor] = explode('#', $path, 2);
        $anchor = '#' . $anchor;
    }
    $url = AFF_BASE . '/' . ltrim($path, '/');
    $url = rtrim($url, '/') . '/';
    if (AFF_ID !== '') {
        $url .= '?' . AFF_PARAM . '=' . rawurlencode(AFF_ID);
    }
    return $url . $anchor;
}

/** Shorthand for echoing escaped output. */
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/** Cache-buster so a re-uploaded css/js/mp3 is not served stale. */
function v(string $rel): string {
    $abs = __DIR__ . '/' . $rel;
    return $rel . (is_file($abs) ? '?v=' . filemtime($abs) : '');
}
