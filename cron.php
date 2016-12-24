<?php
require_once 'bootstrap.php';

define('CRON_LOG', true);

(new Sebsel\Lees\Errandboy())->go();
