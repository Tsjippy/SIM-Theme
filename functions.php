<?php
namespace SIMTHEME;

define('THEME', 'sim-theme');

// composer
require 'lib/vendor/autoload.php';

$files = glob(__DIR__  . '/php/*.php');
foreach ($files as $file) {
    require_once($file);
}

//wp_enqueue_script('sim_theme_main_script', "$baseUrl/js/main.min.js", array(), wp_get_theme()->get('Version'), true);