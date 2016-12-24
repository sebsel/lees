<?php template('header') ?>

<form method="post" action="/subscribe">
  <input type="url" name="url">
  <input type="submit" value="Subscribe">
</form>

  <?php foreach ($subscriptions as $sub): ?>

    <div>
      <?=$sub->url()?>
      <a href="/unsubscribe?url=<?=urlencode($sub->filename())?>">Unsub</a>
    </div>

  <?php endforeach; ?>

<?php template('footer') ?>
