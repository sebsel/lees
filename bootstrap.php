<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'toolkit' . DIRECTORY_SEPARATOR . 'bootstrap.php');

require_once(__DIR__ . DS . 'src'    . DS . 'helpers.php');
require_once(__DIR__ . DS . 'config' . DS . 'config.php');

date_default_timezone_set('UTC');

$src = __DIR__ . DS . 'src';
$obj = __DIR__ . DS . 'src' . DS . 'obj';

load([
  'sebsel\\lees\\reader'    => $src . DS . 'reader.php',
  'sebsel\\lees\\errandboy' => $src . DS . 'errandboy.php',
  'sebsel\\lees\\mf2'       => $src . DS . 'mf2.php',

  'sebsel\\lees\\entry'         => $obj . DS . 'entry.php',
  'sebsel\\lees\\author'        => $obj . DS . 'author.php',
  'sebsel\\lees\\subscription'  => $obj . DS . 'subscription.php',
  'sebsel\\lees\\year'          => $obj . DS . 'year.php',
]);

define('ENTRIES_DIR',       __DIR__ . DS . 'data' . DS . 'entries');
define('SUBSCRIPTIONS_DIR', __DIR__ . DS . 'data' . DS . 'subscriptions');
