<?php
require_once 'bootstrap.php';

c::set('filter-read', true);

router([
  [
    'pattern' => '/',
    'action' => function() {

      (new Sebsel\Lees\App())->start();

    }
  ],
  [
    'pattern' => 'read/(:num)/(:num)/(:any)',
    'action' => function($year, $day, $entry) {

      try {
        $entry = new \Sebsel\Lees\Entry($year.'/'.$day.'/'.$entry);
        $entry->toggleRead();

      } catch (Error $e) {
        header::status(500);
        echo "Could not mark post as read";
      }

      go('/');
    }
  ]
]);
