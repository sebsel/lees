<?php template('header') ?>

  <header>
    This is an IndieWeb Reader.
  </header>

  <?php if (!count($entries)): ?>
    Nothing to read! Come back later :)
  <?php endif; ?>

  <?php foreach ($entries as $entry): ?>

    <?php template('entry', ['entry' => $entry]) ?>

  <?php endforeach; ?>

<?php template('footer') ?>
