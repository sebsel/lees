<?php template('header') ?>

  This is an IndieWeb Reader.

  <?php foreach ($entries as $entry): ?>

    <?php template('entry', ['entry' => $entry]) ?>

  <?php endforeach; ?>

<?php template('footer') ?>
