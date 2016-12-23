<div>
  <h1><?= $entry->name() ?></h1>
  <small>by <?=$entry->author() ?></small>
  <p><?= $entry->content() ?></p>
  <a href="<?=$entry->url()?>">Link</a>
</div>