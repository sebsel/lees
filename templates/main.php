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
        <input type="submit" value="<?=l::get('mark-all-read', 'Mark all items as read')?>" class="right">
      <?php endif; ?>
    </footer>

  </form>

  <?php else: ?>
    <?=l::get('no-entries', 'Nothing to read! Come back later :)')?>
  <?php endif; ?>

<?php template('footer') ?>
