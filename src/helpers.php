<?php

function template($name, $data = []) {
  tpl::$data = $data;

  $file = dirname(__DIR__) . DS . 'templates' . DS . $name .'.php';

  if (file_exists($file)) echo tpl::load($file, null, true);
}

function fdate($format, $date) {
  return strftime($format, strtotime($date));
}

function router($routes) {
  $router = new Router($routes);
  $route = $router->run();

  if (isset($route) and is_callable($route->action)) {
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
