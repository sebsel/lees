<?php template('header') ?>

  <header>
    <?=l::get('headline', 'This is an Indieweb Reader')?>
  </header>

  <?php if (!count($entries)): ?>
    <?=l::get('no-entries', 'Nothing to read! Come back later :)')?>
  <?php endif; ?>

  <?php foreach ($entries as $entry): ?>

    <?php template('entry', ['entry' => $entry]) ?>

  <?php endforeach; ?>

<?php template('footer') ?>
