<?php

namespace Sebsel\Lees;

use Obj, Str, Yaml, C, F, Url;

class Subscription extends Obj {

  public $filename;
  public $time;
  public $convertedUrl;

  function __construct($filename) {
    if (f::exists(SUBSCRIPTIONS_DIR.DS.$filename)) {
      $this->filename = $filename;
      $data = yaml::read(SUBSCRIPTIONS_DIR.DS.$this->filename);
      parent::__construct($data);
    } else {
      throw new Error('No such subscription');
    }
  }

  function feedurl() {
    if (isset($this->convertedUrl)) return $this->convertedUrl;

    $url = $this->url;

    if (url::host($url) == 'twitter.com' and c::get('twitter-access-token-key', false) and c::get('twitter-access-token-secret', false)) {
      $query = false;

      if (url::path($url) == 'search') {
        $params = url::query($url);
        $query = urlencode($params['q']);

      } elseif (!str::contains(url::path($url), '/')) {
        $query = 'from%3A'.url::path($url);
      }

      if ($query) {
        $url = 'https://granary-demo.appspot.com/twitter/%40me/@search/@app/?search_query='.$query.'&format=html&access_token_key='.c::get('twitter-access-token-key').'&access_token_secret='.c::get('twitter-access-token-secret').'';
      }

    } elseif (url::host($url) == 'instagram.com') {
      $url = 'https://granary-demo.appspot.com/instagram/'.url::path($url).'/@self/@app/?format=html';
    }

    return $this->convertedUrl = $url;
  }

  function setNextTime($log = false) {
    $oldfilename = $this->filename;

    $newtime = time() + (60*60*4);

    // TODO: this line in the try
    $this->filename = $newtime.'-'.str::after($this->filename, '-');

    try {
      f::move(SUBSCRIPTIONS_DIR.DS.$oldfilename, SUBSCRIPTIONS_DIR.DS.$this->filename);

    } catch (Exception $e) {
      throw new Error("Could not enter new time");
    }
  }

  function time() {
    if (isset($this->time)) return $this->time;
    return $this->time = str::before($this->filename, '-');
  }

}
