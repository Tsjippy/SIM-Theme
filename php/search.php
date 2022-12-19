<?php
namespace SIMTHEME;

add_action('init', function(){
    // replace the searchform with the ajax search lite
    if ( shortcode_exists( 'wpdreams_ajaxsearchlite' ) ) {
        add_filter( 'generate_navigation_search_output', function() {
            return sprintf(
                '<div class="navigation-search">%s</div>',
                do_shortcode( '[wpdreams_ajaxsearchlite]' )
            );
        } );
    }
});