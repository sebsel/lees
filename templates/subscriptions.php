<?php template('header') ?>
<header>
  <form method="post" action="/subscribe">
    <input type="url" name="url">
    <input type="submit" value="Subscribe">
  </form>
</header>

  <?php foreach ($subscriptions as $sub): ?>

    <div class="subscription">
      <div class="url"><?=$sub->url()?></div>
      <div class="options">
        next check at <?=strftime('%H:%M', $sub->time()) ?>
        <a href="/unsubscribe?url=<?=urlencode($sub->url())?>" class="right">Unsub</a>
      </div>
    </div>

  <?php endforeach; ?>

<?php template('footer') ?>
