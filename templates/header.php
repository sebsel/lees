<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Lees</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/main.css">
  </head>
  <body>

<div class="container">
  <nav class="menu">
    <a href="/new" class="<?e(url::path() == 'new', 'active')?>">
      <?=l::get('new', 'New')?>
    </a>
    <a href="/read" class="<?e(url::path() == 'read', 'active')?>">
      <?=l::get('read', 'Read')?>
    </a>
    <a href="/archive" class="<?e(url::path() == 'archive', 'active')?>">
      <?=l::get('archive', 'Archive')?>
    </a>
    <a href="/subscriptions" class="<?e(url::path() == 'subscriptions', 'active')?>">
      <?=l::get('subscriptions', 'Subscriptions')?>
    </a>

    <a href="/logout" class="right">
      <?=l::get('logout', 'Logout')?>
    </a>
  </nav>
