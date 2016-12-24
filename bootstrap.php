<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'toolkit' . DIRECTORY_SEPARATOR . 'bootstrap.php');

require_once(__DIR__ . DS . 'src' . DS . 'helpers.php');
require_once(__DIR__ . DS . 'config' . DS . 'config.php');

date_default_timezone_set('UTC');

load([
  'sebsel\\lees\\app' => __DIR__ . DS . 'src' . DS . 'app.php',
  'sebsel\\lees\\reader'  => __DIR__ . DS . 'src' . DS . 'reader.php',
  'sebsel\\lees\\entry'  => __DIR__ . DS . 'src' . DS . 'entry.php',
  'sebsel\\lees\\errandboy'  => __DIR__ . DS . 'src' . DS . 'errandboy.php',
  'sebsel\\lees\\mf2'  => __DIR__ . DS . 'src' . DS . 'mf2.php',
]);

define('CONTENT_DIR', __DIR__ . DS . 'content');
define('SUBSCRIPTIONS_DIR', __DIR__ . DS . 'subscriptions');
