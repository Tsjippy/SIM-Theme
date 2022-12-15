<?php
namespace SIMTHEME;

/**
 * Shows the news Gallery
 */
function showNewsGallery(){
    $postTypes              = array_keys(get_theme_mod('news_posttypes'));
    $maxNewsAge             = get_theme_mod('max_news_age');
    $args                   = array('ignore_sticky_posts' => true,);
    $args['post_type'] 		= $postTypes;
    $args['post_status'] 	= 'publish';

    $args['date_query']		= array(
        array(
            'after' => array(
                'year' => date('Y', strtotime("-$maxNewsAge")),
                'month' => date('m', strtotime("-$maxNewsAge")),
                'day' => date('d'),
            )
        )
    );
    
    //exclude private events and user pages
    $args['meta_query']		= array(
        'relation' => 'AND',
        array(
            'key' => 'onlyfor',
            'compare' => 'NOT EXISTS',
        ),
        array(
            'relation' => 'OR',
            array(
                'key' => 'expirydate'
            ),
            array(
                'key' => 'expirydate',
                'value' => date('Y-m-d'),
                'compare' => '>',
            ),
            array(
                'key' => 'expirydate',
                'compare' => 'NOT EXISTS',
            )
        ),
        array(
            'key'		=> 'user_id',
            'compare'	=> 'NOT EXISTS'
        ),
    );
    
    //If not logged in..
    if ( !is_user_logged_in() ) {
        //Only get news wih the public category
        $blogCategories = [get_cat_ID('Public')];
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $blogCategories,
            ),
        );
        
        //Do not show password protected news
        $args['has_password'] = false;
    }else{
        $user = wp_get_current_user();

        //exclude birthdays and anniversaries
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'events',
                'field'    => 'term_id',
                'terms'    => [
                    get_term_by('slug', 'anniversary','events')->term_id,
                    get_term_by('slug', 'birthday','events')->term_id
                ],
                'operator' => 'NOT IN'
            ),
        );

        //Hide confidential items
        $confidentialGroups	= (array)\SIM\getModuleOption('contentfilter', 'confidential-roles');
        
        if(array_intersect($confidentialGroups, $user->roles)){
            $args['tax_query'][] =
                array(
                    'taxonomy' => 'events',
                    'field'    => 'term_id',
                    'terms'    => [get_cat_ID('Confidential')],
                    'operator' => 'NOT IN'
                );
        }
    }
    
    //Get all the posts using the previously defined arguments
    $loop = new \WP_Query( $args );

    if ( ! $loop->have_posts() ) {
        
        if(get_theme_mod('hide_news_gallery_if_empty', false)){
            return;
        }
        
        //Show message if there is no news
        ?>
        <article id="news">
            <div id="rowwrap">
                <h2 id="news-title">Latest news</h2>
                <div class="row">
                    <article class="news-article">
                        <div class="card card-plain card-blog">
                            <div class="content">
                                <h4 class="card-title entry-title">
                                    <p>There is currently no news</p>
                                </h4>
                            </div>
                        </div>
                    </article>
                </div>
                <div id="newslinkdiv"></div>
            </div>
        </article>
        <?php
        return;
    }

    $allowedHtml = array(
        'br'     => array(),
        'em'     => array(),
        'strong' => array(),
        'i'      => array(),
        'class' => array(),
        'span'   => array(),
    );
    
    $i = 1;
    ?>
    <article id="news">
        <div id="rowwrap">
            <h2 id="news-title">Latest news</h2>
            <div class="row">
                <?php
                while ( $loop->have_posts() ){
                    $loop->the_post();
                    
                    ?>
                    <article class="news-article">
                        <div class="card card-plain card-blog">
                            <?php
                            if ( has_post_thumbnail() ){
                                echo '<div class="card-image">';
                                    echo '<a href="'.get_permalink().'" style="background-image: url('.get_the_post_thumbnail_url().');"></a>';
                                echo '</div>';
                            }
                            ?>
                            <div class="content">
                                <h4 class="card-title entry-title">
                                    <a class="blog-item-title-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
                                        <?php echo wp_kses( force_balance_tags( get_the_title() ), $allowedHtml ); ?>
                                    </a>
                                </h4>
                                <p class="card-description"><?php echo force_balance_tags(wp_kses_post( get_the_excerpt())); ?></p>
                            </div>
                        </div>
                    </article>
                    <?php
                    $i++;
                }
                ?>
            </div>
        <div id="newslinkdiv"><p><a name="newslink" id="newslink" href="'.SITEURL.'/news/">Read all news items →</a></p></div></div>
    </article>
    <?php

    wp_reset_postdata();
}