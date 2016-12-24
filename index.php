<?php
require_once 'bootstrap.php';

router([
  [
    'pattern' => '/',
    'action' => function() {

      (new \Sebsel\Lees\App())->start();

    }
  ],
  [
    'pattern' => 'read/(:num)/(:num)/(:any)',
    'action' => function($year, $day, $entry) {

      try {
        $entry = new \Sebsel\Lees\Entry($year.'/'.$day.'/'.$entry);
        $entry->toggleRead();

      } catch (Error $e) {
        header::status(500);
        echo "Could not mark post as read";
      }

      go('/');
    }
  ],
  [
    'pattern' => '(subscribe|unsubscribe)',
    'method' => 'POST',
    'action' => function($action) {

      $feed = get('url');

      if (!v::url($feed)) die('Url not valid');

      $content = [
        'url' => $feed
      ];

      $filename = str_replace('/',':', url::short($feed)).'.txt';

      $found = false;
      foreach(dir::read(SUBSCRIPTIONS_DIR) as $file) {
        if (str::after($file, '-') == $filename) {
          $found = $file;
          break;
        }
      }

      if ($action == 'subscribe') {
        if (!$found) {
          $filename = '0-'.$filename;
          yaml::write(SUBSCRIPTIONS_DIR.DS.$filename, $content);

          (new \Sebsel\Lees\Errandboy())->fetchPosts($filename);

          go('/');

        } else {
          echo "You're already subscribed";
        }
      } else {
        if ($found) {
          f::remove(SUBSCRIPTIONS_DIR.DS.$found);
          go('/');

        } else {
          echo "No such subscription found";
        }
      }
    }
  ]
]);
