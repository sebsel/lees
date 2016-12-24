<?php

namespace Sebsel\Lees;

use Remote, Error, C;

class Micropub {

  public static function like($url) {
    return static::post([
      'like-of' => $url
    ]);
  }

  public static function bookmark($url) {
    return static::post([
      'bookmark-of' => $url
    ]);
  }

  public static function post($data) {
    if (!$endpoint = c::get('micropub-endpoint', false)
      or !$token = c::get('micropub-access-token', false))
        throw new Error('No endpoint');

    $defaults = [
      'access_token' => $token,
      'h' => 'entry'
    ];

    $data = array_merge($data, $defaults);

    $r = remote::post($endpoint, ['data' => $data]);

    if ($r->code == 201 or $r->code == 202)
      return $r->headers['Location'];

    return false;
  }
}
