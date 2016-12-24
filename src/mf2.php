<?php

namespace Sebsel\Reader;

class Mf2 {

  public static function parse($html, $url = null) {
    require_once dirname(__DIR__).DS.'vendor'.DS.'mf2.php';
    return \Mf2\parse($html, $url);
  }

  public static function toJf2($mf2) {
    require_once dirname(__DIR__).DS.'vendor'.DS.'mf2tojf2'.DS.'mf2tojf2.php';

    return mf2tojf2($mf2);
  }

}