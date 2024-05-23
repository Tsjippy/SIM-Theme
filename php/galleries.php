<?php
namespace SIMTHEME;
use SIM;

// depends on the SIM plugin

function showGalleries(){
    // check the order of the page and news gallery
    $priorities	= get_theme_mod('priority', []);
    if(!isset($priorities['news'])){
        $priorities['news']	= 10;
    }
    if(!isset($priorities['page'])){
        $priorities['page']	= 20;
    }

    // Sort according to the value
    asort($priorities);

    echo "<div id='gallery-wrapper'>";
    foreach($priorities as $what=>$priority){
        if($what == 'news'){
            // Do not show if we should hide it
            if(
                get_theme_mod('hide_news_gallery', false)	||
                (
                    !is_user_logged_in()		&&
                    get_theme_mod('hide_news_gallery_if_not_logged_in', false)
                )
            ){
                continue;
            }
            showNewsGallery();
        }

        if($what == 'page' && function_exists('SIM\getModuleOption') && SIM\getModuleOption('pagegallery', 'enable')){
            // Do not show if we should hide it
            if(
                get_theme_mod('hide_page_gallery', false)	||
                (
                    !is_user_logged_in()		&&
                    get_theme_mod('hide_page_gallery_if_not_logged_in', false)
                )
            ){
                continue;
            }

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

            if(function_exists('SIM\PAGEGALLERY\pageGallery')){
                echo SIM\PAGEGALLERY\pageGallery($title, $postTypes, $amount, $includedCategories, $speed, $showIfEmpty);
            }
        }
    }
    echo "</div>";

    // add js to make sure the galleries are using the full available screen width
    ?>
    <script>
        let gal=document.querySelector("#gallery-wrapper");

        gal.style.marginLeft    = '-'+gal.getBoundingClientRect().left+'px';

        gal.style.width         = `calc(100vw - ${window.innerWidth - document.documentElement.clientWidth}px)`;

        let sidebar             = document.querySelector(`.is-right-sidebar .inside-right-sidebar`);
        
        let minY                = sidebar.getBoundingClientRect().bottom;

        let curY                = gal.getBoundingClientRect().top

        // adjust if needed
        if(curY < minY){
            gal.style.marginTop = minY - curY + 20 + 'px'; // plus 20 px bottom margin
        }
    </script>

    <?php
}