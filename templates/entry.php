<div class="entry <?e($entry->read(), 'read')?>">
  <div class="author">
    <?php if (is_a($entry->author(), 'Obj') and $entry->author()->photo()): ?>
      <img src="<?=$entry->author()->photo() ?>" class="logo">
    <?php endif; ?>

    <?=$entry->author() ?>

    <?php if ($entry->like_of()): ?>
      likes
      <a href="<?php echo $entry->like_of() ?>">this</a>
    <?php endif; ?>
    <?php if ($entry->bookmark_of()): ?>
      bookmarked
      <a href="<?php echo $entry->bookmark_of() ?>">this</a>
    <?php endif; ?>
  </div>

  <div class="inner">
    <?php if ($entry->rating()): ?>
      <?php for ($i=0; $i < $entry->rating()->int(); $i++): ?>
        <img src="/assets/images/star.svg" alt="*" class="icon">
      <?php endfor; ?>
    <?php endif; ?>

    <?php if ($entry->hasName()): ?>
      <h1><?= $entry->name() ?></h1>
    <?php endif; ?>

    <?php if ($entry->photo()): ?>
      <figure>
        <img src="<?=$entry->photo() ?>">
      </figure>
    <?php endif; ?>

    <div class="content">
      <p><?= html($entry->summary()) ?></p>
      <p><?= html($entry->content()) ?></p>
    </div>
    <div class="options">
      <a href="<?=$entry->url()?>" class="date"><?=$entry->published()?></a>
      <a href="/like/?url=<?= urlencode($entry->url()) ?>">Like</a>
      <a href="/bookmark/?url=<?= urlencode($entry->url()) ?>">Bookmark</a>
      <a href="/read/<?=$entry->id()?>">Mark as read</a>
    </div>
  </div>
</div>
