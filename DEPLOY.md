# Uploading this to FastComet

Five minutes, no build step, no composer, no database. PHP 7.4 or newer.

## 1. Set your affiliate link

Open `config.php`. The only two lines that must change:

```php
define('AFF_PARAM', 'ref');                 // the query key your programme uses
define('AFF_ID',    'YOUR-AFFILIATE-ID');   // your identifier
```

If Business Bangerz gave you a finished link rather than an ID — say
`https://businessbangerz.com/?via=rich` — then `AFF_PARAM` is `via` and `AFF_ID` is `rich`.

Every button on the page is built from those two values, so there is no second place to edit.
While `AFF_ID` is left at the placeholder the links still work; they just credit nobody.

## 2. The song

Already done — `buzz bang Teaser.mp3` is in `assets/audio/` as `buzz-bang-teaser.mp3`
(spaces in filenames cause more trouble on shared hosting than they are worth), and `config.php`
points at it:

```php
define('SONG_FILE',   'assets/audio/buzz-bang-teaser.mp3');
define('SONG_TITLE',  'Buzz Bang Teaser');
define('SONG_ARTIST', 'A 47-second argument, sung');
```

To swap in a different track, drop it in the same folder and change those three lines. If the
file named there is missing, the player renders a short note saying so instead of a broken
control strip — an incomplete upload never looks like a broken site.

Host the mp3 on this domain rather than linking to Drive or Dropbox. The waveform reads the audio through
the Web Audio API, which browsers only allow for a same-origin file. A cross-origin mp3 still
plays; the bars just fall back to a decorative animation.

## 3. Set the domain

```php
define('SITE_URL', 'https://your-domain.com');
```

Used for the canonical tag and the link preview when someone shares the page. Getting it wrong
costs nothing visible on the page itself, which is exactly why it is easy to forget.

## 4. Upload

Via cPanel File Manager or SFTP, put the **contents** of this folder into `public_html/`:

```
public_html/
  index.php
  config.php
  .htaccess
  includes/
  assets/
```

Not the `affiliate-site` folder itself — its contents. If you land on a directory listing instead
of the page, that is what happened.

Two things cPanel does that will confuse you if you are not expecting them:

- **`.htaccess` is hidden by default.** In File Manager: Settings → Show Hidden Files. If it did
  not upload, the page still works; you lose compression and caching, not function.
- **Set the PHP version** under cPanel → MultiPHP Manager. 8.0+ preferred, 7.4 is fine.

## 5. Check it

- Page loads, nothing says `Warning:` or `Fatal error:` at the top.
- Press play. The disc spins, the bars move, the time counts up.
- Drag the seek bar to the middle — audio should jump there instantly. If it stalls or restarts,
  the host is not answering range requests; the `Accept-Ranges` header in `.htaccess` handles
  that, so check the file uploaded.
- Right-click a CTA, copy the link, confirm your affiliate ID is on the end of it.
- Open it on a phone. The layout collapses to one column below 620px.

## Editing the words

| What | Where |
|---|---|
| Headlines, hero copy, the finale | `index.php` — plain HTML, no logic to step around |
| Cards, use cases, steps, FAQ | `includes/data.php` — arrays; add or delete rows freely |
| Nav, page title, social preview | `includes/header.php` |
| Affiliate disclosure, footer | `config.php` (`AFF_DISCLOSURE`) and `includes/footer.php` |
| Colours, type, spacing | `assets/css/style.css` — the palette is the `:root` block at the top |

The card grids size themselves from however many array entries exist, so cutting a use case from
six to four needs no CSS change.

## The disclosure is not optional

`AFF_DISCLOSURE` in `config.php` renders in the footer. The FTC expects affiliate relationships
to be disclosed clearly and near the links, and affiliate programmes generally require it in
their terms. Reword it however you like; do not delete it.

## Running it locally first

```
php -S 127.0.0.1:8899 -t .
```

Then open <http://127.0.0.1:8899/>. XAMPP, MAMP or any PHP install works; the site has no
dependencies beyond PHP itself.

One difference worth knowing: PHP's built-in server does not answer byte-range requests, so
seeking mid-song behaves worse locally than it will on Apache. Do not chase that bug — it is not
in the site.
