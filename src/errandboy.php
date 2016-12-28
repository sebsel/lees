<?php
/*
 * Errandboy
 *
 * Walks through the subscriptions and fetches posts from feeds that are due.
 *
 */

namespace Sebsel\Lees;

use Obj, F, Dir, Remote, Str, Yaml, Error;

if (!defined('CRON_LOG')) define('CRON_LOG', false);

class Errandboy {

  function go() {

    $errands = $this->getErrands();

    if (count($errands)) foreach ($errands as $errand) {
      $this->fetchPosts($errand);
    }
  }

  function getErrands() {
    $subs = dir::read(SUBSCRIPTIONS_DIR);
    $subscriptions = [];

    if (count($subs)) foreach ($subs as $k => $sub) {
      $time = str::before($sub, '-');
      if ($time < time()) {
        $subscriptions[$k] = new Subscription($sub);
      }
    }

    return $subscriptions;
  }

  function fetchPosts($feed, $log = false) {

    if (is_string($feed) and f::exists(SUBSCRIPTIONS_DIR.DS.$feed))
      $feed = new Subscription($feed);

    $r = remote::get($feed->feedurl());

    if (CRON_LOG) echo $r->code . " " . $feed->url() . "\n";
    if ($r->code != 200) return;

    $data = mf2::parse($r->content, $feed->url());
    $data = mf2::tojf2($data);

    // TODO: check type=feed and search among children if not
    // Then move this whole thing into a new function and call it on the feed

    if (isset($data['children'])) {

      foreach ($data['children'] as $entry) {
        if ($entry['type'] != 'entry' and $entry['type'] != 'review') continue;

        if (!isset($entry['author']) and isset($data['author']))
          $entry['author'] = $data['author'];

        if (!isset($entry['uid']) and isset($entry['url']))
          $entry['uid'] = $entry['url'];
        if (!isset($entry['uid']) and isset($entry['published']))
          $entry['uid'] = $feed->url().'|'.strtotime($entry['published']);
        if (!isset($entry['uid']))
          $entry['uid'] = uniqid();

        if (!isset($entry['published']))
          $entry['published'] = strftime('%F %T');
        else
          $entry['published'] = strftime('%F %T', strtotime($entry['published']));

        $id = strftime('%Y/%j/%H%M%S', strtotime($entry['published'])) . '-' . substr(sha1($entry['uid']),0,6);

        $content = yaml::encode($entry);

        if (!entry::exists($id)) {
          f::write(ENTRIES_DIR . DS . $id . '.txt', $content);
          if (CRON_LOG) echo " - ".$entry['url']."\n";
        }
      }

      $feed->setNextTime();
      sleep(1);

    } else {
      if (CRON_LOG) echo "no new items\n";
    }
  }

  function getCoffee() {
    return '&#9749;&#65039;';
  }
}
