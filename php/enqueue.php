<?php

add_action( 'wp_enqueue_scripts', function(){
	$baseUrl	= get_bloginfo('stylesheet_directory');
	
    wp_enqueue_style( 'sim_menu_style', "$baseUrl/css/menu.min.css", array(), 9);
});

/**
 * Enqueue styles and scripts for the Customizer pane.
 */
function mytheme_customize_pane_enqueue() {
    wp_enqueue_script( 'mytheme-customizer-control',
        get_bloginfo('stylesheet_directory') . '/js/customizer.js',
        array( 'customize-controls' ), '20180924', true );
}
add_action( 'customize_controls_enqueue_scripts', 'mytheme_customize_pane_enqueue' );