/* ==========================================================================
   The song commercial player.
   No dependencies — a CDN outage should never be the reason the pitch is silent.
   ========================================================================== */
(function () {
  'use strict';

  var audio = document.getElementById('audio');
  var shell = document.getElementById('player');

  // Jump buttons still need to work when no mp3 has been uploaded yet:
  // they scroll to the player, which then explains what is missing.
  var jumps = document.querySelectorAll('[data-play-jump]');
  for (var j = 0; j < jumps.length; j++) {
    jumps[j].addEventListener('click', function () {
      if (shell) shell.scrollIntoView({ behavior: 'smooth', block: 'center' });
      if (audio) {
        if (audio.paused) play(); else audio.pause();
      }
    });
  }

  if (!audio || !shell) return;

  var playBtn = document.getElementById('play');
  var backBtn = document.getElementById('back');
  var fwdBtn  = document.getElementById('fwd');
  var muteBtn = document.getElementById('mute');
  var seek    = document.getElementById('seek');
  var vol     = document.getElementById('vol');
  var curEl   = document.getElementById('cur');
  var durEl   = document.getElementById('dur');
  var canvas  = document.getElementById('viz');

  var seeking = false;

  audio.volume = vol ? vol.value / 100 : 0.9;

  // ── Transport ───────────────────────────────────────────────────────────

  function play() {
    var p = audio.play();
    // Safari and Chrome reject this when the gesture is not trusted; the
    // rejection is not an error worth surfacing, but it must not go unhandled.
    if (p && typeof p.catch === 'function') p.catch(function () {});
  }

  playBtn.addEventListener('click', function () {
    if (audio.paused) play(); else audio.pause();
  });

  backBtn.addEventListener('click', function () {
    audio.currentTime = Math.max(0, audio.currentTime - 10);
  });

  fwdBtn.addEventListener('click', function () {
    audio.currentTime = Math.min(audio.duration || 0, audio.currentTime + 10);
  });

  audio.addEventListener('play', function () {
    shell.classList.add('playing');
    playBtn.setAttribute('aria-label', 'Pause');
    startViz();
  });

  audio.addEventListener('pause', function () {
    shell.classList.remove('playing');
    playBtn.setAttribute('aria-label', 'Play');
  });

  audio.addEventListener('ended', function () {
    shell.classList.remove('playing');
    audio.currentTime = 0;
    paint(0);
  });

  // ── Time and seeking ────────────────────────────────────────────────────

  function fmt(s) {
    if (!isFinite(s) || s < 0) s = 0;
    var m = Math.floor(s / 60);
    var r = Math.floor(s % 60);
    return m + ':' + (r < 10 ? '0' : '') + r;
  }

  function paint(pct) {
    seek.value = Math.round(pct * 1000);
    seek.style.backgroundSize = (pct * 100) + '% 100%';
  }

  audio.addEventListener('loadedmetadata', function () {
    durEl.textContent = fmt(audio.duration);
  });

  audio.addEventListener('timeupdate', function () {
    curEl.textContent = fmt(audio.currentTime);
    if (!seeking && audio.duration) paint(audio.currentTime / audio.duration);
  });

  seek.addEventListener('input', function () {
    seeking = true;
    var pct = seek.value / 1000;
    seek.style.backgroundSize = (pct * 100) + '% 100%';
    if (audio.duration) curEl.textContent = fmt(pct * audio.duration);
  });

  seek.addEventListener('change', function () {
    if (audio.duration) audio.currentTime = (seek.value / 1000) * audio.duration;
    seeking = false;
  });

  // ── Volume ──────────────────────────────────────────────────────────────

  vol.addEventListener('input', function () {
    audio.volume = vol.value / 100;
    audio.muted = audio.volume === 0;
    vol.style.backgroundSize = vol.value + '% 100%';
    muteBtn.textContent = audio.muted ? '🔇' : '🔊';
  });

  var lastVol = audio.volume;
  muteBtn.addEventListener('click', function () {
    if (audio.muted || audio.volume === 0) {
      audio.muted = false;
      audio.volume = lastVol > 0.02 ? lastVol : 0.9;
    } else {
      lastVol = audio.volume;
      audio.muted = true;
    }
    var shown = audio.muted ? 0 : Math.round(audio.volume * 100);
    vol.value = shown;
    vol.style.backgroundSize = shown + '% 100%';
    muteBtn.textContent = audio.muted ? '🔇' : '🔊';
  });

  // ── Keyboard, scoped to the player so the page still scrolls normally ──

  shell.addEventListener('keydown', function (ev) {
    if (ev.target.tagName === 'INPUT') return;
    if (ev.code === 'Space' || ev.key === 'k') {
      ev.preventDefault();
      if (audio.paused) play(); else audio.pause();
    }
  });

  // ── Visualiser ──────────────────────────────────────────────────────────
  // Real FFT bars when Web Audio is available. When it is not — older Safari,
  // or an mp3 served from another origin without CORS — fall back to bars
  // driven by playback position so the card is never a dead rectangle.

  if (!canvas || !canvas.getContext) return;

  var ctx2d = canvas.getContext('2d');
  var analyser = null;
  var bins = null;
  var raf = null;
  var BARS = 56;

  function sizeCanvas() {
    var dpr = window.devicePixelRatio || 1;
    var w = canvas.clientWidth || 600;
    var h = canvas.clientHeight || 90;
    canvas.width = Math.round(w * dpr);
    canvas.height = Math.round(h * dpr);
    ctx2d.setTransform(dpr, 0, 0, dpr, 0, 0);
  }
  sizeCanvas();
  window.addEventListener('resize', sizeCanvas);

  function connect() {
    if (analyser) return;
    var AC = window.AudioContext || window.webkitAudioContext;
    if (!AC) return;
    try {
      var ac = new AC();
      var src = ac.createMediaElementSource(audio);
      analyser = ac.createAnalyser();
      analyser.fftSize = 256;
      analyser.smoothingTimeConstant = 0.78;
      src.connect(analyser);
      analyser.connect(ac.destination);
      bins = new Uint8Array(analyser.frequencyBinCount);
      if (ac.state === 'suspended') ac.resume();
    } catch (err) {
      analyser = null; // fallback path below handles it
    }
  }

  function draw() {
    var w = canvas.clientWidth, h = canvas.clientHeight;
    ctx2d.clearRect(0, 0, w, h);

    var gap = 2;
    var bw = (w - gap * (BARS - 1)) / BARS;
    var t = Date.now() / 240;

    if (analyser) analyser.getByteFrequencyData(bins);

    for (var i = 0; i < BARS; i++) {
      var level;
      if (analyser) {
        // The top of the spectrum is mostly empty on speech-heavy mixes,
        // so only the lower ~70% of bins is spread across the bars.
        var idx = Math.floor(i / BARS * bins.length * 0.7);
        level = bins[idx] / 255;
      } else {
        level = 0.25 + 0.25 * Math.sin(t + i * 0.5) + 0.2 * Math.sin(t * 1.7 + i);
        if (audio.paused) level = 0.06;
      }
      level = Math.max(0.04, Math.min(1, level));

      var bh = level * (h - 8);
      var x = i * (bw + gap);
      var y = h - bh;

      var g = ctx2d.createLinearGradient(0, h, 0, y);
      g.addColorStop(0, '#3fe6d0');
      g.addColorStop(0.55, '#8b5cf6');
      g.addColorStop(1, '#ff4fa3');
      ctx2d.fillStyle = g;

      if (ctx2d.roundRect) {
        ctx2d.beginPath();
        ctx2d.roundRect(x, y, bw, bh, Math.min(bw / 2, 3));
        ctx2d.fill();
      } else {
        ctx2d.fillRect(x, y, bw, bh);
      }
    }

    raf = requestAnimationFrame(draw);
    if (audio.paused && !analyser) stopSoon();
  }

  function stopSoon() {
    // One more frame settles the bars to their floor, then stop burning cycles.
    cancelAnimationFrame(raf);
    raf = null;
  }

  function startViz() {
    connect();
    if (!raf) raf = requestAnimationFrame(draw);
  }

  audio.addEventListener('pause', function () {
    setTimeout(function () {
      if (audio.paused && raf) { cancelAnimationFrame(raf); raf = null; }
    }, 700);
  });

  // Idle state: draw the floor once so the panel reads as a player, not a gap.
  draw();
  setTimeout(function () { if (audio.paused && raf) { cancelAnimationFrame(raf); raf = null; } }, 60);
})();
