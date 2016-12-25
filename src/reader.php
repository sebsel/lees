<?php

namespace Sebsel\Lees;

use Collection;
use Dir;
use F, A, V, C;
use Obj;
use Yaml;

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

  function entries() {
    $articles = $this->more();
    return $articles->sortBy('published', 'desc')->limit(10);
  }

  protected function more($date = null, $num = 10) {

    $articles = new Collection();

    // To prevent counting down the years, get the oldest year
    $beginning = $this->years()->first()->year().'/001';

    // If there is no $day, start today
    if ($date == null) $date = strftime('%Y/%j');

    // If day is a date (YYYY-ddd), look for the first day-page before that
    if (v::match($date, '![0-9]{4}/[0-9]{3}!')) {

      while (!$a = dir::read(ENTRIES_DIR . DS . $date)
        and $date > $beginning) {

        $date = date_decrement($date);
      }

      if (empty($a)) return $articles; // empty Collection here
    }

    // Get the articles from the day and filter them
    foreach ($a as $article) {
      if (c::get('filter-read', true) and $article[0] == '-') continue;

      $articles->append($article, new Entry($date.'/'.$article));
    }

    if ($articles->count() < $num) $articles = $articles->merge(
      $this->more(date_decrement($date), $num - $articles->count())
    );

    return $articles;
  }

  function years() {
    if (isset($this->years)) return $this->years;

    $this->years = new Collection();

    $years = dir::read(ENTRIES_DIR);

    foreach ($years as $year) {
      $this->years->append($year, new Year($year));
    }

    return $this->years;
  }
}
