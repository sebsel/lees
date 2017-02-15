<?php

namespace Sebsel\Lees;

use Obj, A, Url, F, Yaml, Remote;

class Author extends Obj {

  function __construct($data) {

    if (isset($data['url']) and (empty($data['name']) or empty($data['photo']))) {
      $file = HCARDS_DIR.DS.sha1($data['url']).'.txt';

      if (f::exists($file)) {
        $card = yaml::read($file);
        if (isset($card['url'])) $data = $card;

      } else {
        $r = remote::get($data['url']);

        $mf2 = Mf2::toJf2(Mf2::parse($r->content, $data['url']));
        $card = Mf2::findCard($mf2);
        if ($card == false) $card = [];

        yaml::write($file, $card);
        if (isset($card['url'])) $data = $card;
      }
    }

    parent::__construct($data);
  }

  function url() {
    if(is_array($this->url)) return a::first($this->url);
    return $this->url;
  }

  function __toString() {
    if (is_string($this->name))
      return $this->name();

    if (isset($this->url))
      return url::host($this->url());

    return "unknown";
  }
}
