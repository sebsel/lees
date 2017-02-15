<?php template('header') ?>

  <?php if (count($entries)): ?>

  <form action="/read-all" method="POST">
    <header>
      <?php if (url::path()=='new'): ?>
        <input type="submit" value="<?=l::get('mark-all-read', 'Mark all items as read')?>" class="right">
      <?php endif; ?>
    </header>

    <?php foreach ($entries as $entry): ?>

      <?php template('entry', ['entry' => $entry]) ?>

    <?php endforeach; ?>

    <footer class="cf">
      <?php if (url::path()=='new'): ?>
        <?php
        if (0 < ($n = (db::count('entry', ['status' => 'new']) - 10)))
          printf(l::get('n-new-items', '%s new items'), $n);
        ?>
        <input type="submit" value="<?=l::get('mark-all-read', 'Mark all items as read')?>" class="right">
      <?php endif; ?>
    </footer>

  </form>

  <?php else: ?>
    <?=l::get('no-entries', 'Nothing to read! Come back later :)')?>
  <?php endif; ?>

<?php template('footer') ?>
