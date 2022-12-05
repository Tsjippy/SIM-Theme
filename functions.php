<?php

define('THEME', 'sim-theme');

// composer
require 'lib/vendor/autoload.php';

$files = glob(__DIR__  . '/php/*.php');
foreach ($files as $file) {
    require_once($file);
}