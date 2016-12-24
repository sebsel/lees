<div>
  <?php if ($entry->name() and $entry->name() != $entry->content()): ?>
    <h1><?= $entry->name() ?></h1>
  <?php endif; ?>
  <small>by <?=$entry->author() ?></small>
  <p><?= $entry->content() ?></p>
  <a href="<?=$entry->url()?>"><?=$entry->published()?></a>
</div>
<hr>