<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'toolkit' . DIRECTORY_SEPARATOR . 'bootstrap.php');

require_once(__DIR__ . DS . 'src' . DS . 'helpers.php');

date_default_timezone_set('UTC');

load([
  'sebsel\\reader\\app' => __DIR__ . DS . 'src' . DS . 'app.php',
  'sebsel\\reader\\reader'  => __DIR__ . DS . 'src' . DS . 'reader.php',
  'sebsel\\reader\\entry'  => __DIR__ . DS . 'src' . DS . 'entry.php',
]);

define('CONTENT_DIR', __DIR__ . DS . 'content');

(new Sebsel\Reader\App())->start();