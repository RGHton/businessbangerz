# Make It A Banger

An affiliate landing page for [businessbangerz.com](https://businessbangerz.com) — original songs
written for training, launches and policy rollouts.

The page's whole argument is that a song explains the product faster than prose does, so the song
is the centrepiece: a custom player sits in the hero with a live waveform, seek, ±10s and volume,
and every call to action carries the affiliate ID.

## Stack

PHP 7.4+ and nothing else. No composer, no build step, no database, no CDN dependency — the page
renders and the audio plays with the network otherwise on fire. Written for shared hosting
(FastComet/cPanel), where that matters more than it does elsewhere.

```
index.php              layout and copy
config.php             affiliate ID, song, site identity — the only file you must edit
includes/
  data.php             cards, use cases, steps, FAQ as arrays
  header.php           nav, meta, social tags
  footer.php           affiliate disclosure
assets/
  css/style.css        palette in the :root block at the top
  js/player.js         the audio player and canvas visualiser
  audio/               the song
.htaccess              compression, caching, byte-range headers for the mp3
```

## Running it

```
php -S 127.0.0.1:8899 -t .
```

## Deploying

See [DEPLOY.md](DEPLOY.md). Short version: set `AFF_ID` in `config.php`, upload the contents of
this folder to `public_html/`.

## Disclosure

This is an independent affiliate site. It is not operated by Business Bangerz or Neu² Media, and
the footer says so. The FTC disclosure in `config.php` renders on the page; reword it, don't
remove it.
