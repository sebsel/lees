<?php template('header') ?>

  This is an IndieWeb Reader.

  <form method="post" action="/subscribe">
    <input type="url" name="url">
    <input type="submit" value="Subscribe">
  </form>
  <form method="post" action="/unsubscribe">
    <input type="url" name="url">
    <input type="submit" value="Unsubscribe">
  </form>

  <?php foreach ($entries as $entry): ?>

    <?php template('entry', ['entry' => $entry]) ?>

  <?php endforeach; ?>

<?php template('footer') ?>
