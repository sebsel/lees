<?php

function template($name, $data = []) {

  tpl::$data = $data;

  $file = dirname(__DIR__) . DS . 'templates' . DS . $name .'.php';

  if (file_exists($file)) echo tpl::load($file, null, true);

}