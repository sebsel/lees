<?php

function template($name, $data = []) {
  tpl::$data = $data;

  $file = dirname(__DIR__) . DS . 'templates' . DS . $name .'.php';

  if (file_exists($file)) echo tpl::load($file, null, true);
}

function date_decrement($date) {
  $year = substr($date, 0, 4);
  $day  = ((int)substr($date,5,3) - 1);
  if ($day < 1) { $year--; $day = 366; }
  return $year . '/' . str_pad($day, 3, '0', STR_PAD_LEFT);
}