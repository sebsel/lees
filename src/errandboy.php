<?php
/*
 * Errandboy
 *
 * Walks through the subscriptions and fetches posts from feeds that are due.
 *
 */

namespace Sebsel\Reader;

use Obj, F, Dir, Remote, Yaml;

class Errandboy {

  function go() {

    $errands = $this->getErrands();

    foreach ($errands as $errand) {
      $this->fetchPosts($errand);
    }
  }

  function getErrands() {
    $subscriptions = dir::read(SUBSCRIPTIONS_DIR);

    foreach ($subscriptions as $k => $sub) {
      $subscriptions[$k] = f::read(SUBSCRIPTIONS_DIR . DS . $sub);
    }

    return $subscriptions;
  }

  function fetchPosts($feed) {

    $r = remote::get($feed);

    echo $r->code . " ".$feed."<br>\n";

    $data = mf2::parse($r->content, $feed);
    $data = mf2::tojf2($data);

    if (isset($data['children'])) {

      foreach ($data['children'] as $entry) {
        if ($entry['type'] != 'entry' and $entry['type'] != 'review') continue;

        if (!isset($entry['author']) and isset($data['author']))
          $entry['author'] = $data['author'];

        if (!isset($entry['uid']) and isset($entry['url']))
          $entry['uid'] = $entry['url'];
        if (!isset($entry['uid']) and isset($entry['published']))
          $entry['uid'] = $feed.'|'.strtotime($entry['published']);
        if (!isset($entry['uid']))
          $entry['uid'] = uniqid();

        if (!isset($entry['published']))
          $entry['published'] = strftime('%F %T');

        $path = CONTENT_DIR . DS . strftime('%Y/%j', strtotime($entry['published']));

        $filename = strftime('%H%M%S', strtotime($entry['published'])) . '-' . sha1($entry['uid']) . '.txt';

        $content = yaml::encode($entry);

        if (!f::exists($path.DS.$filename)
          and !f::exists($path.DS.'.'.$filename)) {
          dir::make($path);

          f::write($path . DS . $filename, $content);
          echo " - ".$entry['url']."<br>\n";
        }
      }
    } else {
      echo "no new items<br>\n";
    }
  }

  function getCoffee() {
    return '&#9749;&#65039;';
  }
}

class Errand extends Obj {

}