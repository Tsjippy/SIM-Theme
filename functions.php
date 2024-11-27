<?php
namespace SIMTHEME;

use Exception;

define(__NAMESPACE__ .'\THEME', 'sim-theme');
define(__NAMESPACE__ .'\THEME_PATH', str_replace('\\', '/', __DIR__));

//check if plugin is already installed
$activePlugins	= get_option( 'active_plugins' );

// We cannot use the sim theme
if(!in_array('sim-plugin/sim-plugin.php', $activePlugins)){
    $themes = wp_get_themes();
    unset($themes['SIM theme']);

    switch_theme(array_values($themes)[0]['theme_root']);
    error_log("To use the sim-theme you need to install the sim-plugin");
    //throw new Exception("To use the sim-theme you need to install the sim-plugin");
}

// composer
require 'lib/vendor/autoload.php';

$files = glob(__DIR__  . '/php/*.php');
foreach ($files as $file) {
    require_once($file);
}

//wp_enqueue_script('sim_theme_main_script', "$baseUrl/js/main.min.js", array(), wp_get_theme()->get('Version'), true);