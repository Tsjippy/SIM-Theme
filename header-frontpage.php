<?php
namespace SIMTHEME;

/**
 * The template for displaying the frontpage header.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php generate_do_microdata( 'body' ); ?>>
	<?php
	/**
	 * wp_body_open hook.
	 *
	 * @since 2.3
	 */
	do_action( 'wp_body_open' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core WP hook.

	/**
	 * generate_before_header hook.
	 *
	 * @since 0.1
	 *
	 * @hooked generate_do_skip_to_content_link - 2
	 * @hooked generate_top_bar - 5
	 * @hooked generate_add_navigation_before_header - 5
	 */
	do_action( 'generate_before_header' );

	/**
	 * generate_header hook.
	 *
	 * @since 1.3.42
	 *
	 * @hooked generate_construct_header - 10
	 */
	do_action( 'generate_header' );

	$page1		= get_theme_mod('first_button_page');
	$page2		= get_theme_mod('second_button_page');

	$text1		= get_theme_mod('first_button_text');
	$text2		= get_theme_mod('second_button_text');

	if(empty($text1)){
		$text1		= get_the_title($page1);
	}
	if(empty($text2)){
		$text2		= get_the_title($page2);
	}

	$url1		= get_the_permalink($page1);
	$url2		= get_the_permalink($page2);

	$headerImageUrl	= get_theme_mod( 'header_image' ) ;

	?>
	<div id='main-image'>
		<img src='<?php echo $headerImageUrl;?>' alt=''>
		<div id="header-buttons">
			<a id='first_button' href='<?php echo $url1;?>' title='<?php echo $text1;?>' class='btn btn-primary header_button' id='header_button1'><?php echo $text1;?></a>
			<a id='second_button' href='<?php echo $url2;?>' title='<?php echo $text2;?>' class='btn btn-right header_button' id='header_button1'><?php echo $text2;?></a>
		</div>
	</div>
	<?php

	/**
	 * generate_after_header hook.
	 *
	 * @since 0.1
	 *
	 * @hooked generate_featured_page_header - 10
	 */
	do_action( 'generate_after_header' );
	?>

	<div <?php generate_do_attr( 'page' ); ?>>
		<?php
		/**
		 * generate_inside_site_container hook.
		 *
		 * @since 2.4
		 */
		do_action( 'generate_inside_site_container' );
		
		do_action( 'sim_frontpage_before_main_content' );
		?>
		<div <?php generate_do_attr( 'site-content' ); ?>>
			<?php
			/**
			 * generate_inside_container hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_inside_container' );
