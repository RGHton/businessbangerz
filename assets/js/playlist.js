/* ==========================================================================
   "Hear more bangers" playlist. Never autoplays: nothing sounds until a click.
   ========================================================================== */
(function () {
  'use strict';

  // Two songs at once is never what anyone wants, so starting any player
  // pauses every other one on the page, the hero commercial included.
  document.addEventListener('play', function (ev) {
    var all = document.getElementsByTagName('audio');
    for (var i = 0; i < all.length; i++) {
      if (all[i] !== ev.target && !all[i].paused) all[i].pause();
    }
  }, true);

  var shell = document.getElementById('mix');
  var audio = document.getElementById('mix-audio');
  if (!shell || !audio) return;

  var playBtn  = document.getElementById('mix-play');
  var kicker   = document.getElementById('mix-kicker');
  var titleEl  = document.getElementById('mix-title');
  var progress = document.getElementById('mix-progress');
  var tracks   = shell.querySelectorAll('.mix-track');
  var current  = -1;

  function play() {
    var p = audio.play();
    if (p && typeof p.catch === 'function') p.catch(function () {});
  }

  function load(i) {
    current = i;
    audio.src = tracks[i].getAttribute('data-src');
    titleEl.textContent = tracks[i].querySelector('.mix-name').textContent;
    kicker.textContent = 'Song ' + (i + 1) + ' of ' + tracks.length;
    progress.style.width = '0';
    for (var t = 0; t < tracks.length; t++) {
      tracks[t].setAttribute('aria-current', t === i ? 'true' : 'false');
    }
  }

  playBtn.addEventListener('click', function () {
    if (current < 0) { load(0); play(); return; }
    if (audio.paused) play(); else audio.pause();
  });

  for (var i = 0; i < tracks.length; i++) {
    tracks[i].addEventListener('click', function () {
      var idx = +this.getAttribute('data-index');
      if (idx === current) {
        if (audio.paused) play(); else audio.pause();
        return;
      }
      load(idx);
      play();
    });
  }

  audio.addEventListener('play', function () {
    shell.classList.add('playing');
    playBtn.setAttribute('aria-label', 'Pause the playlist');
  });

  audio.addEventListener('pause', function () {
    shell.classList.remove('playing');
    playBtn.setAttribute('aria-label', 'Play the playlist');
  });

  audio.addEventListener('timeupdate', function () {
    if (audio.duration) progress.style.width = (audio.currentTime / audio.duration * 100) + '%';
  });

  // Advance through the list, then stop on the last song rather than looping.
  audio.addEventListener('ended', function () {
    if (current + 1 < tracks.length) {
      load(current + 1);
      play();
    } else {
      kicker.textContent = 'That was all of them. Press play to go again';
      current = -1;
    }
  });
})();
