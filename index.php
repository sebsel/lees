<?php
namespace Sebsel\Lees;

use C, V, F, Str, Yaml, Header, Dir, Url, Error, Remote, S, Cookie;

require_once 'bootstrap.php';

require_login();

router([
  [
    'pattern' => '/',
    'action' => function() {
      go('read');
    }
  ],
  [
    'pattern' => '(new|read|archive)',
    'action' => function($action) {

      if ($action == 'new') {
        $filter = null; // no status
      } elseif($action == 'read') {
        $filter = 'later'; // things you want to read
      } else {
        $filter = 'read'; // archive: already read
      }

      $entries = (new Reader())->entries($filter);

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
    'pattern' => '(read|later)/(:num)/(:num)/(:any)',
    'action' => function($status, $year, $day, $entry) {

      try {
        $entry = new Entry($year.'/'.$day.'/'.$entry);
        $entry->toggleRead($status);

      } catch (Error $e) {
        header::status(500);
        echo "Could not mark post as ".$status;
      }

      go('/');
    }
  ],
  [
    'pattern' => 'read-all',
    'method' => 'POST',
    'action' => function () {
      $later = get('later');

      foreach (get('all') as $entry) {
        if (isset($later[$entry])) $status = 'later';
        else $status = 'read';

        (new Entry($entry))->toggleRead($status);
      }

      go('/new');
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
  [
    'pattern' => 'auth',
    'action' => function() {
      $r = remote::post('https://indieauth.com/auth', [
        'data' => [
          'code' => get('code'),
          'redirect_uri' => url::base().'/auth',
          'client_id' => url::base().'/'
        ]
      ]);

      parse_str($r->content(), $auth);

      if ($auth['me'] == c::get('user')) {
        cookie::set(
          'lees_session',
          sha1(c::get('user') . c::get('salt')),
          60 * 24 * 30
        );
        go('/');
      } else {
        die('Sorry, no entry for you');
      }

      go('/');
    }
  ],
  [
    'pattern' => 'logout',
    'action' => function () {
      cookie::remove('lees_session');
    }
  ],
  [
    'pattern' => '(subscribe|unsubscribe|check-now)',
    'method' => 'GET|POST',
    'action' => function($action) {

      $feed = get('url');

      if (!v::url($feed)) die('Url not valid');

      $content = [
        'url' => $feed
      ];

      $filename = str_replace('/', '-', url::short($feed));
      $filename = f::safeName($filename.'.txt');

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

          go('/subscriptions');

        } else {
          echo "You're already subscribed";
        }

      } elseif ($action == 'unsubscribe') {
        if ($found) {
          f::remove(SUBSCRIPTIONS_DIR.DS.$found);
          go('/subscriptions');

        } else {
          echo "No such subscription found";
        }

      } elseif ($action == 'check-now') {
        if ($found) {
          $name = str::after($found, '-');
          f::move(SUBSCRIPTIONS_DIR.DS.$found, SUBSCRIPTIONS_DIR.DS.'0-'.$name);
          go('/subscriptions');

        } else {
          echo "No such subscription found";
        }
      }
    }
  ]
]);
