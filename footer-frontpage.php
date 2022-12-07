<?php
namespace SIMTHEME;
use SIM;

/**
 * The template for displaying the footer.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

	</div>
</div>

<?php

$priorities	= get_theme_mod('priority', []);
if(!isset($priorities['news'])){
	$priorities['news']	= 10;
}
if(!isset($priorities['page'])){
	$priorities['page']	= 20;
}

// Sort according to the value
asort($priorities);

foreach($priorities as $what=>$priority){

	if($what == 'news' && !get_theme_mod('hide_news_gallery', false)){
		showNewsGallery();
	}

	if($what == 'page' && !get_theme_mod('hide_page_gallery', false) && function_exists('SIM\getModuleOption') && SIM\getModuleOption('pagegallery', 'enable')){
		$postTypes			= get_theme_mod('page_posttypes', []);
		$postTypes			= array_keys(array_filter(
			$postTypes,
			function($value){
				return $value === true;
			},
		));
		$excludedCategories	= get_theme_mod('page_categories', []);

		$includedCategories	= [];

		foreach ( $postTypes as $type ) {
			$includedCategories[$type]	= [];
			
			$taxonomies 	= get_object_taxonomies($type);
			foreach ( $taxonomies as $taxIndex=>$taxonomy ) {
				// create a list of categories
				$categories	= get_categories( array(
					'taxonomy'		=> $taxonomy
				) );

				if(empty($categories)){
					continue;
				}

				$includedCategories[$type][$taxonomy]	= [];

				foreach($categories as $category){
					if(
						!in_array($type, array_keys($excludedCategories))	||
						!in_array($taxonomy, array_keys($excludedCategories[$type]))	||
						!in_array($category->term_id, array_keys($excludedCategories[$type][$taxonomy]))	||
						!$excludedCategories[$type][$taxonomy][$category->term_id]
					){
						$includedCategories[$type][$taxonomy][]	= $category->term_id;
					}
				}
			}
		}

		$title		= get_theme_mod('page-gallery-title', 'See what we do');
		$amount		= get_theme_mod('page-gallery-count', 3);
		$speed		= get_theme_mod('speed', 60);
		$showIfEmpty= get_theme_mod('hide_page_gallery_if_empty', false);

		echo SIM\PAGEGALLERY\pageGallery($title, $postTypes, $amount, $includedCategories, $speed, $showIfEmpty);
	}
}

/**
 * generate_before_footer hook.
 *
 * @since 0.1
 */
do_action( 'generate_before_footer' );
?>

<div <?php generate_do_attr( 'footer' ); ?>>
	<?php
	/**
	 * generate_before_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'generate_before_footer_content' );

	/**
	 * generate_footer hook.
	 *
	 * @since 1.3.42
	 *
	 * @hooked generate_construct_footer_widgets - 5
	 * @hooked generate_construct_footer - 10
	 */
	do_action( 'generate_footer' );

	/**
	 * generate_after_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'generate_after_footer_content' );
	?>
</div>

<?php
/**
 * generate_after_footer hook.
 *
 * @since 2.1
 */
do_action( 'generate_after_footer' );

wp_footer();
?>

</body>
</html>
