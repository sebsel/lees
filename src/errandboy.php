<?php
/*
 * Errandboy
 *
 * Walks through the subscriptions and fetches posts from feeds that are due.
 *
 */

namespace Sebsel\Lees;

use Obj, F, Dir, Remote, Str, Yaml, Error;

class Errandboy {

  function go() {

    $errands = $this->getErrands();

    if(count($errands)) foreach($errands as $errand) {
      $this->fetchPosts($errand);
    }
  }

  function getErrands() {
    $subs = dir::read(SUBSCRIPTIONS_DIR);
    $subscriptions = [];

    if(count($subs)) foreach($subs as $k => $sub) {
      $time = str::before($sub, '-');
      if($time < time()) {
        $subscriptions[$k] = new Subscription($sub);
      }
    }

    return $subscriptions;
  }

  function fetchPosts($feed, $log = false) {

    if(is_string($feed)) {
      $feed = new Subscription($feed);
    }

    $r = remote::get($feed->feedurl());

    if($r->code != 200) {
      echo $r->code . " " . $feed->feedurl() . "\n";
      $feed->setNextTime();
      return;
    }

    $data = mf2::parse($r->content, $feed->url());
    $data = mf2::tojf2($data);
    $hfeed = mf2::findFeed($data);

    if(isset($hfeed['children']))
    foreach($hfeed['children'] as $entry) {
      if($entry['type'] != 'entry' and $entry['type'] != 'review') continue;

      if(!isset($entry['author']) and isset($hfeed['author']))
        $entry['author'] = $hfeed['author'];

      if(!isset($entry['author']))
        $entry['author']['url'] = $feed->url();

      if(!isset($entry['uid']) and isset($entry['url']))
        $entry['uid'] = $entry['url'];
      if(!isset($entry['uid']) and isset($entry['published']))
        $entry['uid'] = $feed->url().'|'.strtotime($entry['published']);
      if(!isset($entry['uid']) and isset($entry['name']))
        $entry['uid'] = sha1($entry['name']);
      if(!isset($entry['uid']))
        $entry['uid'] = uniqid();

      if(!isset($entry['published']))
        $entry['published'] = strftime('%F %T');
      else
        $entry['published'] = strftime('%F %T', strtotime($entry['published']));

      $id = strftime('%Y/%j/%H%M%S', strtotime($entry['published'])) . '-' . substr(sha1($entry['uid']),0,6);

      entry::create($id, $entry);
    }

    $feed->setNextTime();
    sleep(1);
  }

  function getCoffee() {
    return '&#9749;&#65039;';
  }
}
