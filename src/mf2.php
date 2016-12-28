<?php

namespace Sebsel\Lees;

class Mf2 {

  public static function parse($html, $url = null) {
    return \Mf2\parse($html, $url);
  }

  # mf2 to jf2 converter
  # licence cc0
  #  2015 Kevin Marks (python)
  #  2016 Sebastiaan Andeweg (php)
  public static function tojf2($mf2) {
    $items = isset($mf2['items']) ? $mf2['items'] : [];
    $jf2 = static::flattenProperties($items, true);
    return $jf2;
  }

  private static function flattenProperties($items, $isOuter = false) {
    if (is_array($items)) {
      if (count($items) < 1) return [];
      if (count($items) == 1) {
        $item = $items[0];
        if (is_array($item)) {
          if (key_exists('type', $item)) {
            $props = [
              'type' => isset($item['type'][0]) ? substr($item['type'][0],2) : ''
            ];

            $properties = isset($item['properties']) ? $item['properties'] : [];

            foreach ($properties as $k => $prop) {
              $props[$k] = static::flattenProperties($prop);
            }

            $children = isset($item['children']) ? $item['children'] : [];

            if ($children) {
              if (count($children) == 1) {
                $props['children'] = [static::flattenProperties($children)];

              } else {
                $props['children'] = static::flattenProperties($children);
              }
            }
            return $props;

          } elseif (key_exists('value', $item)) {
            return $item['value'];
          } else {
            return '';
          }
        } else {
          return $item;
        }


      } elseif ($isOuter) {
        $children = [];
        foreach ($items as $child) {
          $children[] = static::flattenProperties([$child]);
        }
        return ["children" => $children];

      } else {
        $children = [];
        foreach ($items as $child) {
          $children[] = static::flattenProperties([$child]);
        }
        return $children;
      }
    } else {
      return $items; // not a list, so string
    }
  }
}
