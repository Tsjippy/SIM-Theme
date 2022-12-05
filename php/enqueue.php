<?php

add_action( 'wp_enqueue_scripts', function(){
	$baseUrl	= get_bloginfo('stylesheet_directory');
	
    wp_enqueue_style( 'sim_theme_style', "$baseUrl/css/main.min.css", array(), 9);
});

/**
 * Enqueue styles and scripts for the Customizer.
 */
add_action( 'customize_controls_enqueue_scripts', function() {
    wp_enqueue_script(
        'sim-theme-customizer-control',
        get_bloginfo('stylesheet_directory') . '/js/customizer.js',
        array( 'customize-controls' ),
        '20180924',
        true
    );
});