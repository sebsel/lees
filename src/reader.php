<?php

namespace Sebsel\Lees;

use Collection;
use Dir;
use Db;

class Reader {

  public $years;
  public $subscriptions;

  function subscriptions() {
    if (isset($this->subscriptions)) return $this->subscriptions;

    $this->subscriptions = new Collection();

    $subscriptions = dir::read(SUBSCRIPTIONS_DIR);

    foreach ($subscriptions as $sub) {
      $this->subscriptions->append($sub, new Subscription($sub));
    }

    return $this->subscriptions;
  }

  function entries($status = null, $num = 10, $direction = 'asc') {
    $entries = new Collection();
    $status = $status ? $status : 'new';
    $dblist = db::select('entry', '*', ['status' => $status], 'id '.$direction, 0,$num);

    $n = 0;
    foreach ($dblist as $entry) {
      $entries->append($n++, new Entry($entry->id));
    }

    return $entries;
  }
}
