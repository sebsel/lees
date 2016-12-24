<?php

namespace Sebsel\Lees;

use Obj;
use Yaml;
use A;
use Url;

class Entry extends Obj {

  public $author;

  function author() {
    if (is_array($this->author))
      return $this->author = new Author($this->get('author'));
    return $this->author;
  }

  function content() {
    if (empty($this->content))
      return null;

    if (is_string($this->content))
      return $this->content;

    if (isset($this->content['text']))
      return $this->content['text'];

    return a::first($this->content);
  }
}

class Author extends Obj {
  function __toString() {
    if (isset($this->name))
      return $this->name();

    if (isset($this->url))
      return url::host($this->url());
  }
}
