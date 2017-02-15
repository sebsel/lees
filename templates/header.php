<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Lees</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="icon" href="/assets/images/lees-logo.png" />
    <link rel="apple-touch-icon" href="/assets/images/lees-logo.png" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Lees">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <script>
    /* for iOS webapp mode! */
    (function(document,navigator,standalone) {
      if (standalone in navigator && navigator[standalone]) {
        var curnode,location=document.location,stop=/^(a|html)$/i;
        document.addEventListener("click", function(e) {
          curnode=e.target;
          while (!stop.test(curnode.nodeName)) {
            curnode=curnode.parentNode;
          }
          if ("href" in curnode && (curnode.href.indexOf("http") || ~curnode.href.indexOf(location.host)) && curnode.target == false) {
            e.preventDefault();
            location.href=curnode.href
          }
        },false);
      }
    })(document,window.navigator,"standalone");
    </script>
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
    <a href="/subscriptions" class="<?e(url::path() == 'subscriptions', 'active')?>">
      <?=l::get('subscriptions', 'Subscriptions')?>
    </a>

    <a href="/logout" class="right">
      <?=l::get('logout', 'Logout')?>
    </a>
  </nav>
