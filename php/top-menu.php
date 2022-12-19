<?php
namespace SIMTHEME;
use SIM;

// add top menu
add_action( 'init', function() {
    register_nav_menu('top', __( 'Top menu' ));
} );

add_action('generate_before_header', function(){
    echo "<nav id='top-navigation'>";
        // show logo
        generate_construct_logo();

        $float  = get_theme_mod('top_nav_alignment_setting');

        switch($float){
            case 'center':
                $style  = 'margin-left: auto;margin-right: auto;';
                break;
            case 'left':
                $style  = 'width: 100%;';
                break;
            default:
                $style  = 'margin-left: auto;';
        }

        ?>
        <style>
            #top-menu{
                <?php echo $style;?>
            }
        </style>
        <div id='top-menu-wrapper'>
            <?php
            // print top menu
            wp_nav_menu(
                array(
                    'theme_location'    => 'top',
                    'container'         => 'div',
                    'container_class'   => 'top-nav',
                    'container_id'      => 'top-menu',
                    'menu_class'        => '',
                    'fallback_cb'       => function($options){
                        if(current_user_can('administrator')){
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
                    'items_wrap'        => '<ul id="%1$s" class="%2$s ' . join( ' ', generate_get_element_classes( 'menu' ) ) . '">%3$s</ul>',
                )
            );

            // add menu items
            if ( 'enable' === generate_get_option( 'nav_search' ) ) {
                generate_do_menu_bar_item_container();

                generate_navigation_search();
                remove_action( 'generate_inside_navigation', 'generate_navigation_search' );
            }
            ?>
        </div>
        <?php
        
        // do not add again
        remove_action( 'generate_after_primary_menu', 'generate_do_menu_bar_item_container' );
    echo "</nav>";
});