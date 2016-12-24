<?php

namespace Sebsel\Reader;

use Collection;
use Dir;
use F, A, V;
use Obj;
use Yaml;

class Reader {

  public $years;

  function __construct() {
    $this->years = new Collection();

    $years = dir::read(CONTENT_DIR);

    foreach ($years as $year) {
      $this->years->append($year, new Year($year));
    }
  }

  function entries() {
    return $this->more();
  }

  protected function more($date = null, $num = 20, &$dateTomorrow = null) {

    $articles = new Collection();

    // To prevent counting down the years, get the oldest year
    $beginning = $this->years->first()->year.'/001';

    // If there is no $day, start today
    if ($date == null) $date = strftime('%Y/%j');

    // If day is a date (YYYY-ddd), look for the first day-page before that
    if (v::match($date, '![0-9]{4}/[0-9]{3}!')) {

      while (!$a = dir::read(CONTENT_DIR . DS . $date)
        and $date > $beginning) {

        $date = date_decrement($date);
      }

      if (empty($a)) return $articles; // empty Collection here
    }

    // Get the articles from the day and filter them
    foreach ($a as $article) {
      if ($article[0] == '.') continue;
      $data = yaml::read(CONTENT_DIR . DS . $date . DS . $article);
      $data = array_change_key_case($data, CASE_LOWER);
      $articles->append($article, new Entry($data));
    }

    if ($articles->count() < $num) $articles = $articles->merge(
      $this->more(date_decrement($date), $num - $articles->count())
    );

    return $articles;
  }
}

class Year {

  public $year;
  public $days;

  function __construct($year) {
    $this->year = $year;
  }

  function days() {
    if (isset($this->days)) return $this->days;

    $days = dir::read(CONTENT_DIR . DS . $this->year);

    return $this->days = $days;
  }
}