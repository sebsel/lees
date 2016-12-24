<?php

namespace Sebsel\Lees;

use Obj, Str, Yaml, F;

class Subscription extends Obj {

  public $filename;
  public $time;

  function __construct($filename) {
    if (f::exists(SUBSCRIPTIONS_DIR.DS.$filename)) {
      $this->filename = $filename;
      $data = yaml::read(SUBSCRIPTIONS_DIR.DS.$this->filename);
      parent::__construct($data);
    } else {
      throw new Error('No such subscription');
    }
  }

  function setNextTime($log = false) {
    $oldfilename = $this->filename;

    $newtime = time() + (60*60*4);

    $this->filename = $newtime.'-'.str::after($this->filename, '-');

    try {
      f::move(SUBSCRIPTIONS_DIR.DS.$oldfilename, SUBSCRIPTIONS_DIR.DS.$this->filename);

    } catch (Exception $e) {
      throw new Error("Could not enter new time");
    }

    if (CRON_LOG) echo "> next check on ".strftime('%T %F', $newtime)."\n";
  }

  function time() {
    if (isset($this->time)) return $this->time;
    return $this->time = str::before($this->filename, '-');
  }

}
