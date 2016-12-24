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

function router($routes) {
  $router = new Router($routes);
  $route = $router->run();

  if (is_callable($route->action)) {
    call($route->action, $route->arguments);
  } else {
    header::status(404);
    echo 'Not found.';
  }
}

function require_login() {
  if (url::path() != 'auth'
    and cookie::get('lees_session') != sha1(c::get('user') . c::get('salt'))) {
    template('login');
    exit();
  }
}
