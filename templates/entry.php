<div <?e($entry->read(), 'style="background:grey"')?>>
  <?php if ($entry->name() and $entry->name() != $entry->content()): ?>
    <h1><?= $entry->name() ?></h1>
  <?php endif; ?>

  <?php if ($entry->photo): ?>
    <img src="<?=$entry->photo ?>">
  <?php endif; ?>
  <small>by <?=$entry->author() ?></small>
  <p><?= $entry->content() ?></p>
  <a href="<?=$entry->url()?>"><?=$entry->published()?></a>
  <a href="/read/<?=$entry->id()?>">Mark as read</a>
</div>
<hr>
