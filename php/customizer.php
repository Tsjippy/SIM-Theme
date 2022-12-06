<?php
namespace SIMTHEME;

if (class_exists('WP_Customize_Control')) {
    class WP_Customize_Label_Control extends \WP_Customize_Control {
    /**
         * Render the control's content.
         *
         * @since 3.4.0
         */
        public function render_content() {
            printf(
                '<label class="customize-control-select"><span class="customize-control-title">%s</span></label>',
                $this->label
            );

            $descriptionId   = '_customize-description-' . $this->id;
            if ( ! empty( $this->description ) ) : ?>
                <span id="<?php echo esc_attr( $descriptionId ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif;
        }
    }
}

// Add home page customizer options
if ( ! function_exists( 'simCustomizeRegister' ) ) {
	add_action( 'customize_register', __NAMESPACE__.'\simCustomizeRegister', 30 );

	/**
	 * Add our base options to the Customizer.
	 *
	 * @param WP_Customize_Manager $wpCustomize Theme Customizer object.
	 */
	function simCustomizeRegister( $wpCustomize ) {
        // Add a homepage panel
		$wpCustomize->add_panel(
			'sim_frontpage_panel',
			array(
				'title' => __( 'Home page', 'sim' ),
				'priority' 	=> 20,
			)
		);

        topNavigation($wpCustomize);

        frontpageHeader($wpCustomize);

        frontpageNewsGallery($wpCustomize);

        if(function_exists('SIM\getModuleOption') && \SIM\getModuleOption('pagegallery', 'enable')){
            frontpagePageGallery($wpCustomize);
        }
    }
}

/**
 * Top navigation settings
 */
function topNavigation($wpCustomize){
    $wpCustomize->add_section(
        'sim_layout_top_navigation',
        array(
            'title' => __( 'Top Navigation', 'generatepress' ),
            'priority' => 29,
            'panel' => 'generate_layout_panel',
        )
    );

    $wpCustomize->add_setting(
        'top_nav_alignment_setting',
        array(
            'default' => 'right'
        )
    );

    $wpCustomize->add_control(
        'top_nav_alignment_setting',
        array(
            'type' => 'select',
            'label' => __( 'Top Navigation Alignment', 'sim' ),
            'section' => 'sim_layout_top_navigation',
            'choices' => array(
                'left' => __( 'Left', 'generatepress' ),
                'center' => __( 'Center', 'generatepress' ),
                'right' => __( 'Right', 'generatepress' ),
            ),
            'settings' => 'top_nav_alignment_setting',
            'priority' => 20,
        )
    );
}

/**
 * The options for the frontpage header
 */
function frontpageHeader($wpCustomize){
    $wpCustomize->add_section(
        'sim_header',
        array(
            'title' => __( 'Header image and buttons', 'sim' ),
            'priority' => 10,
            'panel' => 'sim_frontpage_panel',
        )
    );

    $wpCustomize->add_setting(
        'header_image',
    );

    $wpCustomize->add_control(
        new \WP_Customize_Image_Control (
            $wpCustomize,
            'header_image',
            array(
                'label'             => __( 'Frontpage Header Image', 'sim' ),
                'section'           => 'sim_header',
                'settings'          => 'header_image',
                'priority'          => 5,
            )
        )
    );

     $wpCustomize->add_setting(
        'first_button_page'
    );

    $wpCustomize->add_control(
        'first_button_page',
        [
            'label'             => __( 'First button page', 'sim' ),
            'section'           => 'sim_header',
            'settings'          => 'first_button_page',
            'priority'          => 10,
            'type'				=> 'dropdown-pages'
        ]
    );

    $wpCustomize->add_setting(
        'first_button_text',
        [
            'default'			=> get_the_title(get_theme_mod('first_button_page')),
            'sanitize_callback'	=> 'sanitize_text_field',
        ]
    );

    $wpCustomize->add_control(
        'first_button_text',
        [
            'label'             => __( 'First button text', 'sim' ),
            'section'           => 'sim_header',
            'settings'          => 'first_button_text',
            'priority'          => 11
        ]
    );

    $wpCustomize->add_setting(
        'second_button_page',
    );

    $wpCustomize->add_control(
        'second_button_page',
        [
            'label'             => __( 'Second button page', 'sim' ),
            'section'           => 'sim_header',
            'settings'          => 'second_button_page',
            'priority'          => 15,
            'type'				=> 'dropdown-pages'
        ]
    );

    $wpCustomize->add_setting(
        'second_button_text',
        [
            'default'			=> get_the_title(get_theme_mod('second_button_page')),
            'sanitize_callback'	=> 'sanitize_text_field',
        ]
    );

    $wpCustomize->add_control(
        'second_button_text',
        [
            'label'             => __( 'Second button text', 'sim' ),
            'section'           => 'sim_header',
            'settings'          => 'second_button_text',
            'priority'          => 16

        ]
    );
}

/**
 * The options for the frontpage News Gallery
 */
function frontpageNewsGallery($wpCustomize){
    $postTypes = get_post_types( array(
        'public'   => true
    ), 'object' );

    if ( empty( $postTypes ) ) {
        return;
    }

    $wpCustomize->add_section(
        'sim_news_gallery',
        array(
            'title'         => __( 'News Gallery', 'sim' ),
            'priority'      => 10,
            'panel'         => 'sim_frontpage_panel',
            'description' 	=> __( 'Show a gallery of the lastest posted content', 'sim' ),
        )
    );

    $wpCustomize->add_setting(
        "hide_news_gallery"
    );

    $wpCustomize->add_control(
        "hide_news_gallery",
        [
            'type'        	=> 'checkbox',
            'label'         => 'Do not show the news gallery',
            'section'       => 'sim_news_gallery',
            'settings'      => "hide_news_gallery",
            'priority'      => 11
        ]
    );

    $wpCustomize->add_setting(
        "priority[news]",
        [
            'default'   => 10
        ]
    );

    $wpCustomize->add_control(
        "priority[news]",
        [
            'type'        	=> 'number',
            'label'         => __('The priority of this gallery', 'sim'),
            'section'       => 'sim_news_gallery',
            'settings'      => "priority[news]",
            'priority'      => 12,
            'description'   => __('A lower number means that it is shown higher on the page', 'sim'),
            'active_callback' => function(){
                return !get_theme_mod( "hide_news_gallery", false );
            },
        ]
    );

    $wpCustomize->add_setting(
        "max_news_age"
    );

    $wpCustomize->add_control(
        "max_news_age",
        [
            'type'        	=> 'select',
            'label'         => __('Max news age of news items.', 'sim'),
            'section'       => 'sim_news_gallery',
            'settings'      => "max_news_age",
            'priority'      => 20,
            'choices'       => [
                '1 day'   => '1 day',
                '1 week'  => '1 week',
                '2 weeks' => '2 weeks',
                '1 month' => '1 month',
                '2 months'=> '2 months',
                '3 months'=> '3 months',
            ],
            'active_callback' => function(){
                return !get_theme_mod( "hide_news_gallery", false );
            },
        ]
    );

    $wpCustomize->add_setting(
        'label'
    );

    $wpCustomize->add_control(
        new WP_Customize_Label_Control(
            $wpCustomize,
            "news-posttypes-label",
            array(
                'label'         => 'Select the post types you want want to include in the news gallery',
                'section'       => 'sim_news_gallery',
                'priority'      => 20,
                'settings'      => "label",
                'active_callback' => function(){
                    return !get_theme_mod( "hide_news_gallery", false );
                },
            )
        )
    );

    // loop over post types.
    $basePriority   = 30;
    foreach ( array_keys($postTypes) as $index=>$type ) {
        if($type == 'attachment'){
            continue;
        }

        $wpCustomize->add_setting(
            "news_posttypes[$type]",
            [
                'transport'         => 'postMessage'
            ]
        );

        $wpCustomize->add_control(
            "news_posttypes[$type]",
            [
                'type'        		=> 'checkbox',
                'label'             => ucfirst($type).'s',
                'section'           => 'sim_news_gallery',
                'settings'          => "news_posttypes[$type]",
                'priority'          => 20,
                'active_callback' => function(){
                    return !get_theme_mod( "hide_news_gallery", false );
                },
                
            ]
        );
        
        $taxonomies 	= get_object_taxonomies($type);
        foreach ( $taxonomies as $taxIndex=>$taxonomy ) {
            if($taxonomy == 'post_tag'){
                continue;
            }

            // create a list of sub-categories
            $categories	= get_categories( array(
                'taxonomy'		=> $taxonomy,
                'hide_empty' 	=> false,
            ) );

            if(empty($categories)){
                continue;
            }

            $wpCustomize->add_control(
                new WP_Customize_Label_Control(
                    $wpCustomize,
                    "news_labels[$type][$taxonomy]-label",
                    array(
                        'label'         => ucfirst($type).' - '.ucfirst(str_replace('_', ' ', $taxonomy)).":",
                        'section'       => 'sim_news_gallery',
                        'priority'      => $basePriority+($index*10)+$taxIndex,
                        'settings'      => "label",
                        'description' 	=> __( "Select the categories you want to exclude from the gallery", 'sim' ),
                        'active_callback' => function()use($type){
                            return get_theme_mod( "news_posttypes", [] )[$type] && !get_theme_mod( "hide_news_gallery", false );
                        },
                    )
                )
            );

            foreach ( $categories as $category ) {
                $wpCustomize->add_setting(
                    "news_categories[$type][$taxonomy][$category->term_id]"
                );
            
                $wpCustomize->add_control(
                    "news_categories[$type][$taxonomy][$category->term_id]",
                    [
                        'type'        		=> 'checkbox',
                        'label'             => $category->name,
                        'section'           => 'sim_news_gallery',
                        'settings'          => "news_categories[$type][$taxonomy][$category->term_id]",
                        'priority'          => $basePriority+($index*10)+$taxIndex,
                        'active_callback' => function()use($type){
                            return get_theme_mod( "news_posttypes", [] )[$type] && !get_theme_mod( "hide_news_gallery", false );
                        },
                    ]
                );
            }
        }
    }
}

/**
 * The options for the page gallery
 */
function frontpagePageGallery($wpCustomize){
    $postTypes = get_post_types( array(
        'public'   => true
    ), 'object' );

    if ( empty( $postTypes ) ) {
        return;
    }

    $wpCustomize->add_section(
        'sim_page_gallery',
        array(
            'title'         => __( 'Page Gallery', 'sim' ),
            'priority'      => 30,
            'panel'         => 'sim_frontpage_panel',
            'description' 	=> __( 'Choose which post types you would like to include in the gallery', 'sim' ),
        )
    );

    $wpCustomize->add_setting(
        "hide_page_gallery"
    );

    $wpCustomize->add_control(
        "hide_page_gallery",
        [
            'type'        	=> 'checkbox',
            'label'         => __('Do not show the page gallery.', 'sim'),
            'section'       => 'sim_page_gallery',
            'settings'      => "hide_page_gallery",
            'priority'      => 10
        ]
    );

    $wpCustomize->add_setting(
        "priority[page]",
        [
            'default'   => 20
        ]
    );

    $wpCustomize->add_control(
        "priority[page]",
        [
            'type'        	=> 'number',
            'label'         => __('The priority of this gallery', 'sim'),
            'section'       => 'sim_page_gallery',
            'settings'      => "priority[page]",
            'priority'      => 10,
            'description'   => __('A lower number means higher on the page', 'sim'),
            'active_callback' => function(){
                return !get_theme_mod( "hide_page_gallery", false );
            },
        ]
    );

    $wpCustomize->add_setting(
        "page-gallery-title",
        [
            'default'           => __('See what we do', 'sim')
        ]
    );

    $wpCustomize->add_control(
        "page-gallery-title",
        [
            'type'        	=> 'text',
            'label'         => __('Title for the gallery', 'sim'),
            'section'       => 'sim_page_gallery',
            'settings'      => "page-gallery-title",
            'priority'      => 10,
            'active_callback' => function(){
                return !get_theme_mod( "hide_page_gallery", false );
            },
        ]
    );

    $wpCustomize->add_setting(
        "page-gallery-count",
        [
            'validate_callback'	=> 'is_numeric',
            'default'           => 3
        ]
    );

    $wpCustomize->add_control(
        "page-gallery-count",
        [
            'type'        	=> 'number',
            'label'         => __('Amount of pages to show', 'sim'),
            'section'       => 'sim_page_gallery',
            'settings'      => "page-gallery-count",
            'priority'      => 10,
            'active_callback' => function(){
                return !get_theme_mod( "hide_page_gallery", false );
            },
        ]
    );

    $wpCustomize->add_setting(
        "speed",
        [
            'validate_callback'	=> 'is_numeric',
            'default'           => 60
        ]
    );

    $wpCustomize->add_control(
        "speed",
        [
            'type'        	=> 'number',
            'label'         => __('Refreshrate of the pages in seconds', 'sim'),
            'section'       => 'sim_page_gallery',
            'settings'      => "speed",
            'priority'      => 10,
            'active_callback' => function(){
                return !get_theme_mod( "hide_page_gallery", false );
            },
        ]
    );

    $wpCustomize->add_control(
        new WP_Customize_Label_Control(
            $wpCustomize,
            "page-posttypes-label",
            array(
                'label'         => 'Select the post types you want want to include in the page gallery',
                'section'       => 'sim_page_gallery',
                'priority'      => 20,
                'settings'      => "label",
                'active_callback' => function(){
                    return !get_theme_mod( "hide_page_gallery", false );
                },
            )
        )
    );

    // loop over post types.
    $basePriority   = 30;
    foreach ( array_keys($postTypes) as $index=>$type ) {

        if($type == 'attachment'){
            continue;
        }

        $wpCustomize->add_setting(
            "page_posttypes[$type]"
        );

        $wpCustomize->add_control(
            "page_posttypes[$type]",
            [
                'type'        		=> 'checkbox',
                'label'             => ucfirst($type).'s',
                'section'           => 'sim_page_gallery',
                'settings'          => "page_posttypes[$type]",
                'priority'          => 20,
                'active_callback' => function(){
                    return !get_theme_mod( "hide_page_gallery", false );
                },
            ]
        );
        
        $taxonomies 	= get_object_taxonomies($type);
        foreach ( $taxonomies as $taxIndex=>$taxonomy ) {
            if($taxonomy == 'post_tag'){
                continue;
            }

            // create a list of sub-categories
            $categories	= get_categories( array(
                'taxonomy'		=> $taxonomy,
                'hide_empty' 	=> false,
            ) );

            if(empty($categories)){
                continue;
            }

            $wpCustomize->add_control(
                new WP_Customize_Label_Control(
                    $wpCustomize,
                    "page_labels[$type][$taxonomy]-label",
                    array(
                        'label'         => ucfirst($type).' - '.ucfirst(str_replace('_', ' ', $taxonomy)).":",
                        'section'       => 'sim_page_gallery',
                        'priority'      => $basePriority+($index*10)+$taxIndex,
                        'settings'      => "label",
                        'description' 	=> __( "Select the categories you want to exclude from the gallery", 'sim' ),
                        'active_callback' => function()use($type){
                            return get_theme_mod( "page_posttypes", [] )[$type] && !get_theme_mod( "hide_page_gallery", false );
                        },
                    )
                )
            );

            foreach ( $categories as $category ) {
                $wpCustomize->add_setting(
                    "page_categories[$type][$taxonomy][$category->term_id]"
                );
            
                $wpCustomize->add_control(
                    "page_categories[$type][$taxonomy][$category->term_id]",
                    [
                        'type'        		=> 'checkbox',
                        'label'             => $category->name,
                        'section'           => 'sim_page_gallery',
                        'settings'          => "page_categories[$type][$taxonomy][$category->term_id]",
                        'priority'          => $basePriority+($index*10)+$taxIndex,
                        'active_callback' => function()use($type){
                            return get_theme_mod( "page_posttypes", [] )[$type] && !get_theme_mod( "hide_page_gallery", false );
                        },
                    ]
                );
            }
        }
    }
}