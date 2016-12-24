<?php

namespace Sebsel\Lees;

use Obj, Url;

class Author extends Obj {
  function __toString() {
    if (isset($this->name))
      return $this->name();

    if (isset($this->url))
      return url::host($this->url());
  }
}
