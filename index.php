<?php
require __DIR__ . '/config.php';
require __DIR__ . '/includes/data.php';
require __DIR__ . '/includes/header.php';

$songExists = is_file(__DIR__ . '/' . SONG_FILE);
?>
<main id="main">

<!-- HERO -->
<section class="hero" id="top">
  <div class="blob blob-a" aria-hidden="true"></div>
  <div class="blob blob-b" aria-hidden="true"></div>

  <div class="wrap hero-grid">
    <div class="hero-copy">
      <p class="eyebrow">🍔 The pitch is forty-seven seconds &middot; and it rhymes</p>
      <h1 class="display">NOBODY HAS<br>EVER HUMMED<br><span class="hl">A SLIDE.</span></h1>
      <p class="lede">
        Business Bangerz writes original songs for training, launches and policy rollouts &mdash;
        the stuff people forget by lunch, turned into something they quote in Slack for a week.
        Explaining that takes paragraphs. <strong>Playing it takes forty-seven.</strong>
      </p>
      <div class="cta-row">
        <button class="btn btn-pink btn-lg" data-play-jump type="button">&#9654;&nbsp; Play the commercial</button>
        <a class="btn btn-ghost btn-lg" href="<?= e(aff()) ?>" target="_blank" rel="noopener sponsored">🎧 Hit the Jukebox</a>
      </div>
      <p class="microcopy">No email required to listen. The button above is just a button.</p>
    </div>

    <!-- THE PLAYER -->
    <div class="player" id="player">
      <div class="player-head">
        <div class="art" id="art" aria-hidden="true">
          <div class="art-disc"></div>
          <div class="eq"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
        </div>
        <div class="player-meta">
          <p class="kicker">Now cueing</p>
          <p class="song-title"><?= e(SONG_TITLE) ?></p>
          <p class="song-artist"><?= e(SONG_ARTIST) ?></p>
        </div>
      </div>

<?php if ($songExists): ?>
      <audio id="audio" preload="metadata" src="<?= e(v(SONG_FILE)) ?>"></audio>

      <canvas id="viz" class="viz" width="600" height="90" aria-hidden="true"></canvas>

      <div class="scrub">
        <input id="seek" class="seek" type="range" min="0" max="1000" value="0" step="1"
               aria-label="Seek through the song">
        <div class="times"><span id="cur">0:00</span><span id="dur">0:00</span></div>
      </div>

      <div class="controls">
        <button id="back" class="ctl" type="button" aria-label="Back 10 seconds">&#8634;&nbsp;10</button>
        <button id="play" class="ctl ctl-main" type="button" aria-label="Play">
          <span class="ico-play">&#9654;</span><span class="ico-pause">&#10074;&#10074;</span>
        </button>
        <button id="fwd" class="ctl" type="button" aria-label="Forward 10 seconds">10&nbsp;&#8635;</button>
        <div class="vol-wrap">
          <button id="mute" class="ctl ctl-quiet" type="button" aria-label="Mute">🔊</button>
          <input id="vol" class="vol" type="range" min="0" max="100" value="90" aria-label="Volume">
        </div>
      </div>
<?php else: ?>
      <div class="player-empty">
        <p><strong>The song goes here.</strong></p>
        <p>Drop your mp3 into <code>assets/audio/</code> and point <code>SONG_FILE</code> in
           <code>config.php</code> at it. The waveform and the controls appear on their own.</p>
      </div>
<?php endif; ?>

      <a class="player-cta" href="<?= e(aff()) ?>" target="_blank" rel="noopener sponsored">
        Liked that? <span>Get one written for your company &rarr;</span>
      </a>
    </div>
  </div>
</section>

<!-- TICKER -->
<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
<?php for ($i = 0; $i < 2; $i++): foreach ($TICKER as $t): ?>
    <span><?= $t ?></span><span class="star">&#10022;</span>
<?php endforeach; endfor; ?>
  </div>
</div>

<!-- WHY -->
<section class="sec" id="why">
  <div class="wrap">
    <p class="eyebrow">The argument, without the song</p>
    <h2 class="display sec-title">WHY A HOOK BEATS<br>A HANDOUT</h2>
    <div class="grid grid-3">
<?php foreach ($WHY as $row): list($icon, $head, $body) = $row; ?>
      <article class="card card-tilt">
        <span class="card-ico" aria-hidden="true"><?= $icon ?></span>
        <h3><?= $head ?></h3>
        <p><?= $body ?></p>
      </article>
<?php endforeach; ?>
    </div>
  </div>
</section>

<!-- USE CASES -->
<section class="sec sec-alt" id="uses">
  <div class="wrap">
    <p class="eyebrow">Where bangers get used</p>
    <h2 class="display sec-title">EVERY DRY MOMENT<br><span class="hl-cyan">DESERVES A HOOK</span></h2>
    <div class="grid grid-3">
<?php foreach ($USES as $row): list($icon, $head, $body) = $row; ?>
      <article class="card">
        <span class="card-ico" aria-hidden="true"><?= $icon ?></span>
        <h3><?= $head ?></h3>
        <p><?= $body ?></p>
      </article>
<?php endforeach; ?>
    </div>
  </div>
</section>

<!-- STEPS -->
<section class="sec" id="how">
  <div class="wrap">
    <p class="eyebrow">How it actually goes</p>
    <h2 class="display sec-title">THREE STEPS,<br>ONE OF THEM IS FUN</h2>
    <ol class="steps">
<?php foreach ($STEPS as $row): list($n, $head, $body) = $row; ?>
      <li class="step">
        <span class="step-n"><?= $n ?></span>
        <div><h3><?= $head ?></h3><p><?= $body ?></p></div>
      </li>
<?php endforeach; ?>
    </ol>
    <div class="cta-row cta-center">
      <a class="btn btn-pink btn-lg" href="<?= e(aff()) ?>" target="_blank" rel="noopener sponsored">🎵 Book the free 20 minutes</a>
    </div>
  </div>
</section>

<!-- OFFERS -->
<section class="sec sec-alt" id="offers">
  <div class="wrap">
    <p class="eyebrow">Three doors, all of them open</p>
    <h2 class="display sec-title">WHAT YOU CAN GET<br>BEFORE LUNCH</h2>
    <div class="grid grid-3">
<?php foreach ($OFFERS as $row): list($icon, $head, $body, $cta, $path) = $row; ?>
      <article class="card card-offer">
        <span class="card-ico" aria-hidden="true"><?= $icon ?></span>
        <h3><?= $head ?></h3>
        <p><?= $body ?></p>
        <a class="btn btn-ghost" href="<?= e(aff($path)) ?>" target="_blank" rel="noopener sponsored"><?= $cta ?> &rarr;</a>
      </article>
<?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="sec" id="faq">
  <div class="wrap wrap-narrow">
    <p class="eyebrow">Reasonable questions</p>
    <h2 class="display sec-title">FAQ</h2>
<?php foreach ($FAQ as $row): list($q, $a) = $row; ?>
    <details class="faq">
      <summary><?= $q ?></summary>
      <p><?= $a ?></p>
    </details>
<?php endforeach; ?>
  </div>
</section>

<!-- FINAL CTA -->
<section class="finale">
  <div class="wrap wrap-narrow">
    <h2 class="display finale-title">MAKE IT A BANGER.<br><span class="hl">NOT A DECK.</span></h2>
    <p class="lede">Your next all-hands is already on the calendar. It can open with a loading spinner, or it can open with a chorus.</p>
    <div class="cta-row cta-center">
      <a class="btn btn-pink btn-lg" href="<?= e(aff()) ?>" target="_blank" rel="noopener sponsored">🔥 Go to Business Bangerz</a>
      <button class="btn btn-ghost btn-lg" data-play-jump type="button">&#9654;&nbsp; Hear the commercial again</button>
    </div>
  </div>
</section>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
