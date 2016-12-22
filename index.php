<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'toolkit' . DIRECTORY_SEPARATOR . 'bootstrap.php');

require_once(__DIR__ . DS . 'src' . DS . 'helpers.php');

load([
  'sebsel\\reader\\app' => __DIR__ . DS . 'src' . DS . 'app.php',
  'sebsel\\reader\\entry'  => __DIR__ . DS . 'src' . DS . 'entry.php',
]);

(new Sebsel\Reader\App())->start();