<?php

// add top menu
function wpb_custom_new_menu() {
    register_nav_menu('top', __( 'Top menu' ));
}
add_action( 'init', 'wpb_custom_new_menu' );

add_action('generate_before_header', function(){
    echo "<nav id='top-navigation'>";
        // show logo
        generate_construct_logo();

        // make sure its not displayed again
        add_filter('generate_logo_output', function(){
            return '';
        });

        // print top menu
        wp_nav_menu(
            array(
                'theme_location' => 'top',
                'container' => 'div',
                'container_class' => 'top-nav',
                'container_id' => 'top-menu',
                'menu_class' => '',
                'fallback_cb' => function($options){
                    if(user_can(wp_get_current_user()->ID, 'administrator')){
                        $url    = admin_url('nav-menus.php?action=locations');
                        $html   = "<div id='top-menu' class='top-nav'>Please add a menu <a href='$url'>here</a></div>";
                    }else{
                        // Do not show when no menu is defined
                        $html   = '';
                    }

                    if($options['echo']){
                        echo $html;
                    }else{
                        return $html;
                    }
                },
                'items_wrap' => '<ul id="%1$s" class="%2$s ' . join( ' ', generate_get_element_classes( 'menu' ) ) . '">%3$s</ul>',
            )
        );

        // add menu items
        generate_do_menu_bar_item_container();
        
        // do not add again
        remove_action( 'generate_after_primary_menu', 'generate_do_menu_bar_item_container' );
    echo "</nav>";
});

// Add home page customizer options
if ( ! function_exists( 'simCustomizeRegister' ) ) {
	add_action( 'customize_register', 'simCustomizeRegister', 20 );
	/**
	 * Add our base options to the Customizer.
	 *
	 * @param WP_Customize_Manager $wpCustomize Theme Customizer object.
	 */
	function simCustomizeRegister( $wpCustomize ) {
        // Add a homepage section

        $wpCustomize->add_section(
			'sim_frontpage',
			array(
				'title' => __( 'Home page', 'generatepress' ),
				'priority' => 10,
				//'panel' => 'sim_frontpage_panel',
			)
		);

        $wpCustomize->add_setting(
			'generate_settings[frontpage_header_image]',
			array(
				'type'              => 'option',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wpCustomize->add_control(
			new WP_Customize_Image_Control(
				$wpCustomize,
				'generate_settings[frontpage_header_image]',
				array(
					'label'             => __( 'Frontpage Header Image', 'sim' ),
					'section'           => 'sim_frontpage',
					'settings'          => 'generate_settings[frontpage_header_image]',
                    'priority'          => 5,
				)
			)
		);
    }
}


add_action( 'wp_enqueue_scripts', function(){
	$baseUrl	= get_bloginfo('stylesheet_directory');
	wp_register_script('sim_home_script', "$baseUrl/js/home.min.js", array('sweetalert'), 9, true);

	if(in_array(get_the_ID(), SIM\getModuleOption('frontpage','home_page', false)) || is_front_page()){
		wp_enqueue_style( 'sim_frontpage_style', "$baseUrl/css/frontpage.min.css", array(), 9);

		//Add header image selected in customizer to homepage using inline css
		add_filter(	'generate_option_defaults', function($options){
			$options['frontpage_header_image']	= '';
			return $options;
		});
		
		$headerImageUrl	= generate_get_option( 'frontpage_header_image' ) ;
		if(!empty($headerImageUrl)){
			$extraCss			= ".home:not(.sticky) #masthead{background-image: url($headerImageUrl);";
			wp_add_inline_style('sim_frontpage_style', $extraCss);
		}
		
		//home.js
		wp_enqueue_script('sim_home_script');
	}
});