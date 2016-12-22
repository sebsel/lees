<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'toolkit' . DIRECTORY_SEPARATOR . 'bootstrap.php');

require_once(__DIR__ . DS . 'src' . DS . 'helpers.php');

load([
  'reader' => __DIR__ . DS . 'src' . DS . 'reader.php',
  'entry'  => __DIR__ . DS . 'src' . DS . 'entry.php',
]);

(new Reader())->start();