<?php
namespace SIMTHEME;

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'wp_enqueue_scripts', function(){
	$baseUrl	= get_bloginfo('stylesheet_directory');
	wp_register_script('sim_home_script', "$baseUrl/js/home.min.js", array('sweetalert'), wp_get_theme()->get('Version'), true);
	wp_enqueue_style( 'sim_frontpage_style', "$baseUrl/css/frontpage.min.css", array(), wp_get_theme()->get('Version'));
	
	//home.js
	wp_enqueue_script('sim_home_script');
	
});

get_header('frontpage'); ?>

	<div <?php generate_do_attr( 'content' ); ?>>
		<main <?php generate_do_attr( 'main' ); ?>>
			<?php
			/**
			 * generate_before_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_before_main_content' );
			do_action( 'sim_frontpage_before_main_content' );

			if ( generate_has_default_loop() ) {
				while ( have_posts() ) :

					the_post();

					generate_do_template_part( 'page' );

				endwhile;
			}

			showGalleries();
			
			/**
			 * generate_after_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_after_main_content' );
			?>
		</main>
	</div>

	<?php
	/**
	 * generate_after_primary_content_area hook.
	 *
	 * @since 2.0
	 */
	do_action( 'generate_after_primary_content_area' );

	generate_construct_sidebars();

	get_footer('frontpage');