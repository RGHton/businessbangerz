<?php if (!defined('AFF_BASE')) { http_response_code(403); exit('Direct access denied.'); } ?>
<footer class="foot">
  <div class="wrap">
    <div class="foot-top">
      <div>
        <p class="foot-brand"><?= e(SITE_NAME) ?></p>
        <p class="foot-note">Made by someone who got a compliance-training song stuck in their head and decided that was worth a website.</p>
      </div>
      <a class="btn btn-cyan" href="<?= e(aff()) ?>" target="_blank" rel="noopener sponsored">Go to businessbangerz.com →</a>
    </div>
    <p class="disclosure"><strong>Affiliate disclosure.</strong> <?= AFF_DISCLOSURE ?></p>
    <p class="foot-legal">&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. Business Bangerz is a trademark of its owner; this page claims no affiliation beyond the affiliate programme.</p>
  </div>
</footer>
<script src="<?= e(v('assets/js/player.js')) ?>" defer></script>
</body>
</html>
