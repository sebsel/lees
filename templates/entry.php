<div class="entry <?= isset($inside) ? 'inside' : 'outside'?>">
  <?=$entry->drawContext() ?>

  <div class="author">

    <?php
    if ($entry->repost_of()) {
      printf(
        l::get('reposted-by', '%s reposted by'),
        html::a(
          $entry->repost_of()->url(),
          ($entry->repost_of()->photo() ? html::img($entry->repost_of()->photo(), ['class' => 'logo']) : '')
          . ' ' . $entry->repost_of()->author(),
          ['class' => 'name']
        )
      );
    }
    ?>

    <?php if (is_a($entry->author(), 'Obj')): ?>
      <?php if ($entry->author()->photo()): ?>
        <img src="<?=$entry->author()->photo() ?>" class="logo">
      <?php endif; ?>

      <a href="<?= $entry->author()->url() ?>" class="name">
        <?=$entry->author() ?>
      </a>

    <?php else: ?>
      <?=$entry->author() ?>
    <?php endif; ?>

    <?php
      if ($entry->like_of()) {
        printf(
          l::get('likes', 'likes %s'),
          html::a(
            $entry->like_of(),
            l::get('this', 'this')
          )
        );
      }

      if ($entry->bookmark_of()) {
        printf(
          l::get('bookmarked', 'bookmarked %s'),
          html::a(
            $entry->bookmark_of(),
            l::get('this', 'this')
          )
        );
      }

      if ($entry->in_reply_to()) {
        printf(
          l::get('replied', 'replied to %s'),
          html::a(
            $entry->in_reply_to(),
            url::host($entry->in_reply_to())
            // TODO: make this a h-card
          )
        );
      }
    ?>
  </div>

  <div class="inner">

    <?php if ($entry->rating()): ?>
      <?php for ($i=0; $i < $entry->rating()->int(); $i++): ?>
        <img src="/assets/images/star.svg" alt="*" class="icon">
      <?php endfor; ?>
    <?php endif; ?>

    <?php if ($entry->hasName()): ?>
      <a href="<?=$entry->url()?>" target="_blank" rel="noopener noreferrer">
        <h1><?= $entry->name() ?></h1>
      </a>
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
    <?if (!isset($inside)):?>
    <div class="options">
      <a href="/like/?url=<?= urlencode($entry->url()) ?>">
        <?= l::get('like', 'Like') ?>
      </a>
      <a href="https://quill.p3k.io/new?reply=<?= urlencode($entry->url()) ?>">
        <?= l::get('reply', 'Reply') ?>
      </a>
      <a href="/bookmark/?url=<?= urlencode($entry->url()) ?>">
        <?= l::get('bookmark', 'Bookmark') ?>
      </a>
      <? if (url::path()=='new'): ?>
        <label for="later-<?=$entry->id()?>">
          <?= l::get('read-later', 'Read later') ?>
          <input type="hidden" name="all[]" value="<?=$entry->id()?>">
          <input type="checkbox" name="later[<?=$entry->id()?>]" id="later-<?=$entry->id()?>">
        </label>
      <? else: ?>
        <a href="/read/<?=$entry->id()?>">
          <?= l::get('mark-as-read', 'Mark as read') ?>
        </a>
      <? endif ?>
    </div>
    <?endif?>

    <a href="<?=$entry->url()?>" class="date" target="_blank" rel="noopener noreferrer">
      <?=fdate(l::get('datetime', '%c'), $entry->published())?>
    </a>

  </div>
</div>
