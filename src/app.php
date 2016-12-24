<?php

namespace Sebsel\Lees;

class App {

  public $reader;

  function __construct() {
    $this->reader = new Reader();
  }

  function start() {

    $entries = $this->reader->entries();

    template('main', [
      'entries' => $entries
    ]);
  }

}
