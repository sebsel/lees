<?php

namespace Sebsel\Reader;

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

  function entries() {

    $entries = [];

    for ($i=0; $i < 3; $i++) {
      $entries[] = new Entry([
        'name' => 'entry'.$i,
        'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        'url' => 'https://example.com/entry'.$i
      ]);
    }

    return $entries;
  }

}