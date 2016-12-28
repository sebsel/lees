<?php template('header') ?>
<header>
  <form method="post" action="/subscribe">
    <input type="url" name="url">
    <input type="submit" value="<?= l::get('subscribe', 'Subscribe') ?>">
  </form>
</header>

  <?php foreach ($subscriptions as $sub): ?>

    <div class="subscription">
      <div class="url"><?=$sub->url()?></div>
      <div class="options">
        <a href="/unsubscribe?url=<?=urlencode($sub->url())?>" class="right"><?=l::get('unsubscribe', 'Unsubscribe')?></a>
      </div>

      <div class="date">
        <?=l::get('next-check', 'Next check at') . strftime(' %H:%M', $sub->time()) ?>
      </div>
    </div>

  <?php endforeach; ?>

<?php template('footer') ?>
