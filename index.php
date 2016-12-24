<?php
namespace Sebsel\Lees;

use V, F, Str, Yaml, Header, Dir, Url, Error;

require_once 'bootstrap.php';

router([
  [
    'pattern' => '/',
    'action' => function() {

      $entries = (new Reader())->entries();

      template('main', [
        'entries' => $entries
      ]);
    }
  ],
  [
    'pattern' => 'subscriptions',
    'action' => function() {

      $subscriptions = (new Reader())->subscriptions();

      template('subscriptions', [
        'subscriptions' => $subscriptions
      ]);
    }
  ],
  [
    'pattern' => 'read/(:num)/(:num)/(:any)',
    'action' => function($year, $day, $entry) {

      try {
        $entry = new Entry($year.'/'.$day.'/'.$entry);
        $entry->toggleRead();

      } catch (Error $e) {
        header::status(500);
        echo "Could not mark post as read";
      }

      go('/');
    }
  ],
  [
    'pattern' => '(like|bookmark)',
    'method' => 'GET|POST',
    'action' => function($action) {

      $url = get('url');

      if (!v::url($url)) die('Url not valid');

      if ($action == 'like') {
        $link = micropub::like($url);
      } else {
        $link = micropub::bookmark($url);
      }

      if ($link) {
        // This is a hack to trigger the kirby-webmentions plugin to send mentions
        remote::get($link);
        go('/');
      } else {
        echo "Micropub endpoint rejected the post";
      }
    }
  ],
    'pattern' => '(subscribe|unsubscribe)',
    'method' => 'GET|POST',
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

          (new Errandboy())->fetchPosts($filename);

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
