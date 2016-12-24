<?php

namespace Sebsel\Lees;

use Obj, Dir;

class Year extends Obj {

  public $year;
  public $days;

  function __construct($year) {
    $this->year = $year;
  }

  function days() {
    if (isset($this->days)) return $this->days;

    $days = dir::read(ENTRIES_DIR . DS . $this->year);

    return $this->days = $days;
  }
}
