<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// 1. Theme Setup
function shashkevych_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'menus' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'shashkevych' ),
        'footer_1'  => __( 'Footer Menu 1', 'shashkevych' ),
        'footer_2'  => __( 'Footer Menu 2', 'shashkevych' ),
        'footer_policies'  => __( 'Footer Policies Menu', 'shashkevych' ),
    ) );
}
add_action( 'after_setup_theme', 'shashkevych_setup' );

// Filter nav menu items to add classes
function shashkevych_nav_menu_link_attributes( $atts, $item, $args ) {
    if ( $args->theme_location === 'primary' ) {
        $atts['class'] = 'nav-link text-[15px] font-normal leading-[1.5] text-[#45474D] hover:text-ag-black transition-colors';
    } elseif ( strpos( $args->theme_location, 'footer_' ) === 0 ) {
        $atts['class'] = (isset($atts['class']) ? $atts['class'] . ' ' : '') . 'nav-link inline-block hover:text-ag-black transition-colors';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'shashkevych_nav_menu_link_attributes', 10, 3 );

// 2. Enqueue Scripts and Styles
function shashkevych_enqueue_scripts() {
    $css_ver = file_exists( get_template_directory() . '/dist/style.css' ) ? filemtime( get_template_directory() . '/dist/style.css' ) : '1.0.0';
    $js_ver = file_exists( get_template_directory() . '/dist/main.js' ) ? filemtime( get_template_directory() . '/dist/main.js' ) : '1.0.0';

    wp_enqueue_style( 'shashkevych-style', get_template_directory_uri() . '/dist/style.css', array(), $css_ver );
    wp_enqueue_script( 'shashkevych-script', get_template_directory_uri() . '/dist/main.js', array(), $js_ver, array( 'strategy' => 'defer', 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'shashkevych_enqueue_scripts' );

function shashkevych_enqueue_block_editor_assets() {
    $css_ver = file_exists( get_template_directory() . '/dist/style.css' ) ? filemtime( get_template_directory() . '/dist/style.css' ) : '1.0.0';
    wp_enqueue_style( 'shashkevych-editor-style', get_template_directory_uri() . '/dist/style.css', array(), $css_ver );
}
add_action( 'enqueue_block_editor_assets', 'shashkevych_enqueue_block_editor_assets' );

// 3. Register Custom Post Type: Projects
function shashkevych_register_cpt_projects() {
    $labels = array(
        'name'                  => _x( 'Projects', 'Post Type General Name', 'shashkevych' ),
        'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'shashkevych' ),
        'menu_name'             => __( 'Projects', 'shashkevych' ),
        'all_items'             => __( 'All Projects', 'shashkevych' ),
        'add_new_item'          => __( 'Add New Project', 'shashkevych' ),
        'add_new'               => __( 'Add New', 'shashkevych' ),
    );
    $args = array(
        'label'                 => __( 'Project', 'shashkevych' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_rest'          => true,
        'has_archive'           => true,
        'rewrite'               => array( 'slug' => 'projects' ),
    );
    register_post_type( 'project', $args );
}
add_action( 'init', 'shashkevych_register_cpt_projects', 0 );

function shashkevych_project_archive_order( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'project' ) ) {
        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'shashkevych_project_archive_order' );

// 4. Polylang Strings Registration
function shashkevych_register_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        // Register Theme Options for translation
        pll_register_string( 'Theme Options', carbon_get_theme_option('shashkevych_contact_title') ?: 'Get in touch', 'shashkevych', false );
        pll_register_string( 'Theme Options', carbon_get_theme_option('shashkevych_contact_text') ?: 'Feel free to contact me', 'shashkevych', true );
        pll_register_string( 'Theme Options', carbon_get_theme_option('shashkevych_footer_text') ?: '© ' . date('Y') . '.', 'shashkevych', true );
    }
}
add_action( 'init', 'shashkevych_register_strings' );

// 4.1 Inline Translations Helper
function theme_t($uk, $en, $ru) {
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'uk';
    if ($lang === 'ru') return $ru;
    if ($lang === 'en') return $en;
    return $uk;
}

// 5. Custom Gutenberg Category
function shashkevych_block_categories( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'shashkevych-blocks',
                'title' => __( 'Theme Blocks', 'shashkevych' ),
                'icon'  => 'layout',
            ),
        )
    );
}
add_filter( 'block_categories_all', 'shashkevych_block_categories', 10, 2 );

// 6. Carbon Fields Integration
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;

function crb_attach_theme_options() {
    // Global Theme Options
    Container::make( 'theme_options', __( 'Theme Options', 'shashkevych' ) )
        ->add_fields( array(
            Field::make( 'text', 'shashkevych_logo_text', __( 'Logo Text', 'shashkevych' ) ),
            Field::make( 'text', 'shashkevych_email', __( 'Contact Email', 'shashkevych' ) ),
            Field::make( 'text', 'shashkevych_contact_title', __( 'Contact Title', 'shashkevych' ) ),
            Field::make( 'textarea', 'shashkevych_contact_text', __( 'Contact Text', 'shashkevych' ) ),
            Field::make( 'textarea', 'shashkevych_footer_text', __( 'Footer Copyright', 'shashkevych' ) ),
            Field::make( 'complex', 'shashkevych_social_links', __( 'Social Links', 'shashkevych' ) )
                ->add_fields( array(
                    Field::make( 'text', 'title', __( 'Title', 'shashkevych' ) ),
                    Field::make( 'text', 'url', __( 'URL', 'shashkevych' ) ),
                ) ),
        ) );

    // Post Meta for Projects
    Container::make( 'post_meta', __( 'Project Details', 'shashkevych' ) )
        ->where( 'post_type', '=', 'project' )
        ->add_fields( array(
            Field::make( 'text', 'project_client', __( 'Client', 'shashkevych' ) ),
            Field::make( 'text', 'project_role', __( 'Role', 'shashkevych' ) ),
            Field::make( 'text', 'project_year', __( 'Year', 'shashkevych' ) ),
            Field::make( 'text', 'project_link', __( 'External Link', 'shashkevych' ) ),
            Field::make( 'text', 'project_video', __( 'Cover Video URL', 'shashkevych' ) )->set_help_text('Direct MP4 URL or YouTube/Vimeo embed'),
        ) );
}
add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );

// 7. Register Carbon Fields Gutenberg Blocks
function crb_attach_blocks() {
    
    // 7.1 Hero Section
    Block::make( __( 'Hero Section' ) )
        ->set_category( 'shashkevych-blocks' )
        ->add_fields( array(
            Field::make( 'text', 'heading', __( 'Heading' ) ),
            Field::make( 'textarea', 'subheading', __( 'Subheading' ) ),
            Field::make( 'image', 'image', __( 'Side Image' ) )->set_value_type( 'url' ),
            Field::make( 'text', 'btn_primary_text', __( 'Primary Button Text' ) ),
            Field::make( 'text', 'btn_primary_url', __( 'Primary Button URL' ) ),
            Field::make( 'text', 'btn_secondary_text', __( 'Secondary Button Text' ) ),
            Field::make( 'text', 'btn_secondary_url', __( 'Secondary Button URL' ) ),
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            ?>
            <section class="breakout relative min-h-screen flex flex-col items-center justify-center pt-20 md:pt-32 pb-12 md:pb-20 text-center overflow-hidden mb-8 md:mb-12">
                <!-- Particles Container -->
                <div id="hero-particles" class="absolute inset-0 z-0 pointer-events-none"></div>

                <div class="relative z-10 max-w-4xl mx-auto px-6">
                    <h1 class="text-4xl md:text-6xl lg:text-[72px] font-[450] text-ag-black leading-[1.1] md:leading-[1.2] mb-6 tracking-tight" data-reveal="letters">
                        <?php echo esc_html( $fields['heading'] ); ?>
                    </h1>
                    <p class="text-[16px] font-normal text-gray-500 leading-relaxed max-w-2xl mx-auto mb-10" data-reveal="rise">
                        <?php echo esc_html( $fields['subheading'] ); ?>
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-4">
                        <?php if ( $fields['btn_primary_text'] && $fields['btn_primary_url'] ) : ?>
                        <a href="<?php echo esc_url( $fields['btn_primary_url'] ); ?>" class="btn-animated" data-reveal="rise" data-delay="2">
                            <div class="btn-animated-bg"></div>
                            <div class="btn-animated-content">
                                <div class="btn-animated-text">
                                    <span><?php echo esc_html( $fields['btn_primary_text'] ); ?></span>
                                    <span><?php echo esc_html( $fields['btn_primary_text'] ); ?></span>
                                </div>
                            </div>
                        </a>
                        <?php endif; ?>
                        <?php if ( $fields['btn_secondary_text'] && $fields['btn_secondary_url'] ) : ?>
                        <a href="<?php echo esc_url( $fields['btn_secondary_url'] ); ?>" class="btn-animated btn-animated-ghost" data-reveal="rise" data-delay="3">
                            <div class="btn-animated-bg"></div>
                            <div class="btn-animated-content">
                                <div class="btn-animated-text">
                                    <span><?php echo esc_html( $fields['btn_secondary_text'] ); ?></span>
                                    <span><?php echo esc_html( $fields['btn_secondary_text'] ); ?></span>
                                </div>
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            <?php
        } );

    // 7.2 Featured Projects
    Block::make( __( 'Featured Projects' ) )
        ->set_category( 'shashkevych-blocks' )
        ->add_fields( array(
            Field::make( 'text', 'title', __( 'Section Title' ) )->set_default_value('Selected Projects'),
            Field::make( 'text', 'link_text', __( 'All Projects Link Text' ) )->set_default_value('View all'),
            Field::make( 'text', 'link_url', __( 'All Projects URL' ) ),
            Field::make( 'association', 'selected_projects', __( 'Selected Projects' ) )
                ->set_types( array(
                    array(
                        'type'      => 'post',
                        'post_type' => 'project',
                    )
                ) ),
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            $selected = $fields['selected_projects'];
            if ( ! empty( $selected ) ) {
                $project_ids = wp_list_pluck( $selected, 'id' );
                $projects = new WP_Query(array(
                    'post_type'      => 'project',
                    'post__in'       => $project_ids,
                    'orderby'        => 'post__in',
                    'posts_per_page' => -1,
                ));
            } else {
                $projects = new WP_Query(array(
                    'post_type' => 'project',
                    'posts_per_page' => 3,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
            }
            ?>
            <section class="breakout py-8 md:py-12 mb-8 md:mb-16 overflow-hidden bg-white relative">
                <div class="max-w-[1400px] mx-auto px-4 lg:px-6 mb-8 md:mb-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <h2 class="text-[36px] font-[450] text-ag-black tracking-tight m-0" data-reveal="letters"><?php echo esc_html($fields['title']); ?></h2>
                        <?php if ( $fields['link_text'] && $fields['link_url'] ) : ?>
                        <a href="<?php echo esc_url($fields['link_url']); ?>" class="btn-animated btn-animated-ghost hidden md:inline-flex" data-reveal="rise" data-delay="1">
                            <div class="btn-animated-bg"></div>
                            <div class="btn-animated-content">
                                <div class="btn-animated-text">
                                    <span><?php echo esc_html($fields['link_text']); ?></span>
                                    <span><?php echo esc_html($fields['link_text']); ?></span>
                                </div>
                                <div class="btn-animated-icon">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true" class="opacity-80"><path d="M1 7H13M8 2L13 7L8 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true" class="opacity-80"><path d="M1 7H13M8 2L13 7L8 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="relative w-full overflow-hidden" data-reveal="rise">
                    <div class="swiper featured-projects-swiper !pl-4 lg:!pl-[calc(50vw-700px)]">
                        <div class="swiper-wrapper">
                            <?php 
                            $i = 1;
                            if ( $projects->have_posts() ) : 
                                while ( $projects->have_posts() ) : $projects->the_post(); 
                                    $client = carbon_get_the_post_meta('project_client');
                                    $year = carbon_get_the_post_meta('project_year');
                                    $video = carbon_get_the_post_meta('project_video');
                                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
                            ?>
                            <div class="swiper-slide w-[85vw] md:w-[70vw] lg:w-[900px] shrink-0 pr-4 md:pr-10 box-border h-auto"
                                 data-client="<?php echo esc_attr($client); ?>"
                                 data-year="<?php echo esc_attr($year); ?>"
                                 data-title="<?php echo esc_attr(get_the_title()); ?>"
                                 data-excerpt="<?php echo esc_attr(wp_trim_words( get_the_excerpt(), 25 )); ?>"
                                 data-url="<?php the_permalink(); ?>"
                            >
                                <div class="card-hover group flex flex-col transition-all h-full">
                                    <div class="project-image-wrapper relative aspect-[16/10] bg-ag-light rounded-[32px] overflow-hidden cursor-pointer border border-black" data-project-url="<?php the_permalink(); ?>">
                                        <?php if ( $video ) : ?>
                                        <iframe src="<?php echo esc_url($video); ?>" class="w-full h-full pointer-events-none object-cover" allow="autoplay; encrypted-media" allowfullscreen loading="lazy"></iframe>
                                        <?php elseif ( $thumbnail ) : ?>
                                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" loading="lazy" />
                                        <?php else : ?>
                                        <div class="w-full h-full flex items-center justify-center bg-ag-light">
                                            <span class="text-6xl font-black text-gray-200 select-none"><?php echo substr(get_the_title(), 0, 1); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- Custom Cursor Button -->
                                        <div class="watch-case-btn absolute top-0 left-0 w-[150px] h-[52px] bg-white text-ag-black font-semibold text-[15px] flex items-center justify-center gap-2 rounded-full pointer-events-none z-20 transition-transform duration-300 shadow-lg scale-0 opacity-0" style="margin-top: -26px; margin-left: -75px;">
                                            <svg width="8" height="12" viewBox="0 0 8 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 1L7 7L1 13"/></svg>
                                            <?php echo theme_t('Дивитись кейс', 'Watch case', 'Смотреть кейс'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                $i++;
                                endwhile; 
                                wp_reset_postdata();
                            endif; 
                            ?>
                            <!-- Spacer slide so the last real slide aligns to the left -->
                            <div class="swiper-slide w-[15vw] md:w-[30vw] lg:w-[calc(100vw-900px-(50vw-700px))] shrink-0 pointer-events-none opacity-0"></div>
                        </div>
                    </div>
                </div>

                <!-- Text Container & Arrows -->
                <div class="max-w-[1400px] mx-auto px-4 lg:px-6 mt-6 md:mt-10 flex flex-col md:flex-row justify-between relative" data-reveal="rise">
                    <div class="w-full lg:w-[800px] relative overflow-hidden min-h-[180px] md:min-h-[250px]">
                        <div id="project-text-container" class="absolute inset-0 flex flex-col w-full transition-transform duration-500 ease-in-out">
                            <div class="text-content-wrapper flex flex-col h-full w-full">
                                <span class="project-client-year text-[11px] font-black uppercase tracking-widest text-gray-400 mb-3 block">
                                    <!-- Populated via JS -->
                                </span>
                                <h3 class="project-title text-[16px] font-[450] tracking-tight mb-2 leading-snug">
                                    <span class="inline-block text-ag-black"></span>
                                </h3>
                                <div class="project-excerpt text-[16px] font-[350] text-gray-500 leading-relaxed line-clamp-2 md:line-clamp-3 mb-8 max-w-xl">
                                </div>
                                <a href="#" class="project-link btn-animated btn-animated-ghost self-start">
                                    <div class="btn-animated-bg"></div>
                                    <div class="btn-animated-content">
                                        <div class="btn-animated-text">
                                            <span><?php echo theme_t('Дивитись кейс', 'View case', 'Смотреть проект'); ?></span>
                                            <span><?php echo theme_t('Дивитись кейс', 'View case', 'Смотреть проект'); ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Arrows placed horizontally aligned with the right edge of the slide area (w-[900px] typically) on desktop -->
                    <div class="project-slider-arrows flex items-center justify-center gap-1 bg-[#F8F9FC] p-1 rounded-full w-max mx-auto md:mx-0 relative md:absolute z-10 mt-6 md:mt-0">
                        <button aria-label="Previous" class="swiper-btn-prev custom-arrow-btn w-9 h-9 rounded-full bg-transparent flex items-center justify-center transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </button>
                        <button aria-label="Next" class="swiper-btn-next custom-arrow-btn w-9 h-9 rounded-full bg-transparent flex items-center justify-center transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    </div>
                </div>



            </section>
            <?php
        } );

    // 7.3 Collaborators
    Block::make( __( 'Collaborators Marquee' ) )
        ->set_category( 'shashkevych-blocks' )
        ->add_fields( array(
            Field::make( 'text', 'title', __( 'Title' ) ),
            Field::make( 'text', 'subtitle', __( 'Subtitle' ) ),
            Field::make( 'complex', 'logos', __( 'Logos' ) )
                ->add_fields( array(
                    Field::make( 'image', 'image', __( 'Logo Image' ) )->set_value_type( 'url' ),
                    Field::make( 'text', 'name', __( 'Company Name' ) ),
                    Field::make( 'text', 'url', __( 'URL (optional)' ) ),
                ) ),
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            if ( empty($fields['logos']) ) return;
            $logos = array_merge($fields['logos'], $fields['logos'], $fields['logos'], $fields['logos']);
            ?>
            <section class="breakout py-8 md:py-12 mb-8 md:mb-16 overflow-hidden bg-white">
                <div class="max-w-[1400px] mx-auto px-4 lg:px-6 mb-8 md:mb-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <h2 class="text-[36px] font-[450] text-ag-black tracking-tight m-0" data-reveal="letters"><?php echo esc_html($fields['title']); ?></h2>
                        <?php if ( $fields['subtitle'] ) : ?>
                            <p class="text-[16px] font-normal text-gray-500 max-w-md m-0 text-left md:text-right" data-reveal="rise" data-delay="1">
                                <?php echo esc_html($fields['subtitle']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="relative w-full overflow-hidden py-0 md:py-12" data-reveal="rise">
                    
                    <!-- Desktop: Container moves horizontally via JS -->
                    <div class="hidden md:flex w-max items-center js-scroll-marquee will-change-transform">
                        <?php 
                        foreach ( $logos as $index => $logo ) : 
                            $delay = $index * 0.25;
                        ?>
                        <a href="<?php echo esc_url($logo['url'] ?: '#'); ?>" <?php if($logo['url']) echo 'target="_blank" rel="noopener noreferrer"'; ?> 
                           style="animation-delay: -<?php echo $delay; ?>s;"
                           class="partner-pill group flex items-center justify-center mx-1 px-8 py-4 bg-[#F9F9FC] border border-[rgba(33,34,38,0.06)] rounded-full hover:border-[rgba(33,34,38,0.15)] hover:shadow-sm transition-colors duration-300" 
                           title="<?php echo esc_attr($logo['name']); ?>">
                            <?php if ( $logo['image'] ) : ?>
                                <img src="<?php echo esc_url($logo['image']); ?>" alt="<?php echo esc_attr($logo['name']); ?>" class="h-7 object-contain grayscale opacity-70 group-hover:opacity-100 group-hover:grayscale-0 transition-all duration-300" />
                            <?php else : ?>
                                <span class="text-[15px] font-[450] text-ag-black">
                                    <?php echo esc_html($logo['name']); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Mobile: Flex Wrap -->
                    <div class="flex md:hidden flex-wrap justify-center gap-3">
                        <?php 
                        foreach ( $fields['logos'] as $logo ) : 
                        ?>
                        <a href="<?php echo esc_url($logo['url'] ?: '#'); ?>" <?php if($logo['url']) echo 'target="_blank" rel="noopener noreferrer"'; ?> 
                           class="partner-pill flex items-center justify-center px-6 py-3 bg-[#F9F9FC] border border-[rgba(33,34,38,0.06)] rounded-full" 
                           title="<?php echo esc_attr($logo['name']); ?>">
                            <?php if ( $logo['image'] ) : ?>
                                <img src="<?php echo esc_url($logo['image']); ?>" alt="<?php echo esc_attr($logo['name']); ?>" class="h-6 object-contain grayscale opacity-70" />
                            <?php else : ?>
                                <span class="text-[14px] font-[450] text-ag-black">
                                    <?php echo esc_html($logo['name']); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php
        } );

    // 7.4 Latest Writing
    Block::make( __( 'Latest Writing' ) )
        ->set_category( 'shashkevych-blocks' )
        ->add_fields( array(
            Field::make( 'text', 'title', __( 'Section Title' ) ),
            Field::make( 'text', 'link_text', __( 'All Writing Link Text' ) ),
            Field::make( 'text', 'link_url', __( 'All Writing URL' ) ),
            Field::make( 'association', 'selected_posts', __( 'Selected Posts' ) )
                ->set_types( array(
                    array(
                        'type'      => 'post',
                        'post_type' => 'post',
                    )
                ) ),
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            $selected = $fields['selected_posts'];
            if ( ! empty( $selected ) ) {
                $post_ids = wp_list_pluck( $selected, 'id' );
                $posts = new WP_Query(array(
                    'post_type'      => 'post',
                    'post__in'       => $post_ids,
                    'orderby'        => 'post__in',
                    'posts_per_page' => -1,
                ));
            } else {
                $posts = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
            }
            ?>
            <section class="mb-12 md:mb-32">
                <div class="flex items-end justify-between mb-3">
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight text-ag-black" data-reveal="letters"><?php echo esc_html($fields['title']); ?></h2>
                    <?php if ( $fields['link_text'] && $fields['link_url'] ) : ?>
                    <a href="<?php echo esc_url($fields['link_url']); ?>" class="btn-ghost" data-reveal="left">
                        <?php echo esc_html($fields['link_text']); ?>
                        <svg width="12" height="10" viewBox="0 0 12 10" fill="none" aria-hidden="true"><path d="M1 5H11M6.5 1L11 5L6.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
                <span class="section-line mb-6 md:mb-10" data-reveal="line"></span>

                <div class="grid grid-cols-1 md:grid-cols-3">
                    <?php 
                    $i = 1;
                    if ( $posts->have_posts() ) : 
                        while ( $posts->have_posts() ) : $posts->the_post(); 
                    ?>
                    <a href="<?php the_permalink(); ?>" class="card-hover group block p-6 transition-colors border-b md:border-b-0 md:border-r border-gray-200 md:[&:nth-child(3n)]:border-r-0 last:border-0 md:last:border-r-0" data-reveal="rise" data-delay="<?php echo $i; ?>">
                        <h3 class="text-sm font-bold text-ag-black group-hover:text-ag-accent transition-colors mb-2 leading-snug">
                            <?php the_title(); ?>
                        </h3>
                        <div class="text-xs text-gray-500 leading-relaxed line-clamp-3">
                            <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                        </div>
                    </a>
                    <?php 
                        $i++;
                        endwhile; 
                        wp_reset_postdata();
                    endif; 
                    ?>
                </div>
            </section>
            <?php
        } );

    // 7.5 About Section
    Block::make( __( 'About Section' ) )
        ->set_category( 'shashkevych-blocks' )
        ->add_fields( array(
            Field::make( 'text', 'heading', __( 'Heading' ) ),
            Field::make( 'image', 'image', __( 'Profile Image' ) )->set_value_type( 'url' ),
        ) )
        ->set_inner_blocks( true )
        ->set_inner_blocks_template( array(
            array( 'core/paragraph', array( 'placeholder' => 'Write about yourself here...' ) ),
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            ?>
            <div class="max-w-[1400px] mx-auto px-4 lg:px-6 my-6 md:my-24">
                <div class="flex flex-col md:flex-row gap-8 lg:gap-20 items-stretch">
                    <div class="flex-1 w-full text-left py-4">
                        <h1 class="text-[36px] md:text-[48px] font-[450] tracking-tight text-ag-black mb-6" data-reveal="letters">
                            <?php echo esc_html( $fields['heading'] ); ?>
                        </h1>
                        <article class="prose prose-lg max-w-none text-[16px] md:text-[18px] text-gray-500 font-[350] leading-relaxed mx-auto md:mx-0" data-reveal="typewriter">
                            <?php echo $inner_blocks; ?>
                        </article>
                    </div>

                    <?php if ( $fields['image'] ) : ?>
                    <div class="w-full md:w-[400px] lg:w-[450px] shrink-0 relative rounded-[32px] md:rounded-[40px] overflow-hidden transform transition-transform duration-700 ease-out scale-95 opacity-0 min-h-[300px] shadow-2xl" data-reveal="zoom">
                        <img src="<?php echo esc_url( $fields['image'] ); ?>" alt="<?php echo esc_attr( $fields['heading'] ); ?>" class="absolute inset-0 w-full h-full object-cover object-center" />
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        } );

    // 7.4 Split Promo
    Block::make( __( 'Split Promo' ) )
        ->set_category( 'shashkevych-blocks' )
        ->add_fields( array(
            Field::make( 'text', 'left_tag', __( 'Left Tag' ) )->set_default_value('Available at no charge'),
            Field::make( 'text', 'left_title', __( 'Left Title' ) )->set_default_value('For developers'),
            Field::make( 'textarea', 'left_text', __( 'Left Text' ) )->set_default_value('Achieve new heights'),
            Field::make( 'text', 'left_btn_text', __( 'Left Button Text' ) )->set_default_value('Download'),
            Field::make( 'text', 'left_btn_url', __( 'Left Button URL' ) ),
            
            Field::make( 'text', 'right_tag', __( 'Right Tag' ) )->set_default_value('Now Available!'),
            Field::make( 'text', 'right_title', __( 'Right Title' ) )->set_default_value('For organizations'),
            Field::make( 'textarea', 'right_text', __( 'Right Text' ) )->set_default_value('Level up your entire team'),
            Field::make( 'text', 'right_btn_text', __( 'Right Button Text' ) )->set_default_value('Read More'),
            Field::make( 'text', 'right_btn_url', __( 'Right Button URL' ) ),
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            ?>
            <section class="split-promo-block breakout relative py-12 md:py-20 lg:py-32 overflow-hidden bg-white border-y border-[rgba(33,34,38,0.05)]">
                <div class="split-promo-canvas absolute inset-0 z-0 pointer-events-none"></div>
                <div class="max-w-[1400px] mx-auto px-4 lg:px-6 relative z-10 flex flex-col md:flex-row min-h-[400px] md:min-h-[600px] gap-4 md:gap-8">
                    <!-- Left Side -->
                    <div class="flex-1 flex flex-col items-center justify-center text-center split-promo-left group relative">
                        <span class="text-[11px] font-semibold px-3 py-1.5 border border-gray-100 rounded-full mb-6 bg-white/50 backdrop-blur-md shadow-[0_2px_10px_rgba(0,0,0,0.03)] uppercase tracking-wider text-gray-500">
                            <?php echo esc_html($fields['left_tag']); ?>
                        </span>
                        <h2 class="text-3xl md:text-4xl lg:text-[40px] font-[450] text-ag-black tracking-tight mb-2">
                            <?php echo esc_html($fields['left_title']); ?>
                        </h2>
                        <p class="text-[16px] md:text-[20px] font-[350] text-gray-500 mb-8 max-w-sm leading-relaxed">
                            <?php echo esc_html($fields['left_text']); ?>
                        </p>
                        <?php if ( $fields['left_btn_text'] && $fields['left_btn_url'] ) : ?>
                        <a href="<?php echo esc_url($fields['left_btn_url']); ?>" class="btn-animated pointer-events-auto shadow-lg hover:shadow-xl transition-shadow">
                            <div class="btn-animated-bg"></div>
                            <div class="btn-animated-content">
                                <div class="btn-animated-text">
                                    <span><?php echo esc_html($fields['left_btn_text']); ?></span>
                                    <span><?php echo esc_html($fields['left_btn_text']); ?></span>
                                </div>
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>
                    <!-- Right Side -->
                    <div class="flex-1 flex flex-col items-center justify-center text-center split-promo-right group relative">
                        <span class="text-[11px] font-semibold px-3 py-1.5 border border-gray-100 rounded-full mb-6 bg-white/50 backdrop-blur-md shadow-[0_2px_10px_rgba(0,0,0,0.03)] uppercase tracking-wider text-gray-500">
                            <?php echo esc_html($fields['right_tag']); ?>
                        </span>
                        <h2 class="text-3xl md:text-4xl lg:text-[40px] font-[450] text-ag-black tracking-tight mb-2">
                            <?php echo esc_html($fields['right_title']); ?>
                        </h2>
                        <p class="text-[16px] md:text-[20px] font-[350] text-gray-500 mb-8 max-w-sm leading-relaxed">
                            <?php echo esc_html($fields['right_text']); ?>
                        </p>
                        <?php if ( $fields['right_btn_text'] && $fields['right_btn_url'] ) : ?>
                        <a href="<?php echo esc_url($fields['right_btn_url']); ?>" class="btn-animated btn-animated-ghost pointer-events-auto shadow-sm hover:shadow-md transition-shadow">
                            <div class="btn-animated-bg"></div>
                            <div class="btn-animated-content">
                                <div class="btn-animated-text">
                                    <span><?php echo esc_html($fields['right_btn_text']); ?></span>
                                    <span><?php echo esc_html($fields['right_btn_text']); ?></span>
                                </div>
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            <?php
        } );

    // 7.7 Latest Blogs
    Block::make( __( 'Latest Blogs' ) )
        ->set_category( 'shashkevych-blocks' )
        ->add_fields( array(
            Field::make( 'text', 'title', __( 'Section Title' ) )->set_default_value('Latest Blogs'),
            Field::make( 'text', 'link_text', __( 'View blog Link Text' ) )->set_default_value('View blog'),
            Field::make( 'text', 'link_url', __( 'View blog URL' ) ),
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            $posts_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 8,
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            $raw_posts = array();
            if ( $posts_query->have_posts() ) {
                while ( $posts_query->have_posts() ) {
                    $posts_query->the_post();
                    $raw_posts[] = array(
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                        'link' => get_the_permalink(),
                        'excerpt' => get_the_excerpt(),
                        'date' => get_the_date('M j, Y'),
                        'category' => get_the_category() ? get_the_category()[0]->name : '',
                        'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'large')
                    );
                }
                wp_reset_postdata();
            }

            if (empty($raw_posts)) return;

            $slider_posts = $raw_posts;
            while(count($slider_posts) < 8) {
                $slider_posts = array_merge($slider_posts, $raw_posts);
            }
            $slider_posts = array_slice($slider_posts, 0, 8);
            ?>
            <section class="breakout py-8 md:py-12 mb-8 md:mb-16 overflow-hidden bg-white relative">
                <div class="max-w-[1400px] mx-auto px-4 lg:px-6 mb-8 md:mb-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <h2 class="text-[36px] font-[450] text-ag-black tracking-tight m-0" data-reveal="letters"><?php echo esc_html($fields['title']); ?></h2>
                        <?php if ( $fields['link_text'] && $fields['link_url'] ) : ?>
                        <a href="<?php echo esc_url($fields['link_url']); ?>" class="btn-animated btn-animated-ghost hidden md:inline-flex" data-reveal="rise" data-delay="1">
                            <div class="btn-animated-bg"></div>
                            <div class="btn-animated-content">
                                <div class="btn-animated-text">
                                    <span><?php echo esc_html($fields['link_text']); ?></span>
                                    <span><?php echo esc_html($fields['link_text']); ?></span>
                                </div>
                                <div class="btn-animated-icon">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true" class="opacity-80"><path d="M1 7H13M8 2L13 7L8 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true" class="opacity-80"><path d="M1 7H13M8 2L13 7L8 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="relative max-w-[1400px] mx-auto px-4 lg:px-6" data-reveal="rise">
                    <div class="swiper blogs-slider w-full overflow-visible">
                        <div class="swiper-wrapper">
                            <?php foreach($slider_posts as $post) : ?>
                            <div class="swiper-slide cursor-pointer group !h-auto">
                                <a href="<?php echo esc_url($post['link']); ?>" class="block h-full flex flex-col">
                                    <div class="w-full aspect-square bg-[#111111] rounded-[20px] overflow-hidden mb-6 flex items-center justify-center relative shadow-sm transition-transform duration-500 group-hover:scale-[1.02]">
                                        <?php if($post['thumbnail']) : ?>
                                            <img src="<?php echo esc_url($post['thumbnail']); ?>" alt="<?php echo esc_attr($post['title']); ?>" class="w-full h-full object-cover">
                                        <?php else : ?>
                                            <span class="text-white text-6xl font-bold opacity-80"><?php echo esc_html(mb_substr($post['title'], 0, 1)); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="text-[16px] font-[450] tracking-tight text-ag-black mb-2 leading-snug group-hover:text-ag-accent transition-colors duration-300">
                                        <?php echo esc_html($post['title']); ?>
                                    </h3>
                                    <div class="text-[16px] font-[350] text-gray-500 leading-relaxed line-clamp-2 md:line-clamp-3 mb-8">
                                        <?php echo wp_trim_words( $post['excerpt'], 20 ); ?>
                                    </div>
                                    <div class="project-link btn-animated btn-animated-ghost self-start mt-auto">
                                        <div class="btn-animated-bg"></div>
                                        <div class="btn-animated-content">
                                            <div class="btn-animated-text">
                                                <span><?php echo theme_t('Читати блог', 'Read blog', 'Читать блог'); ?></span>
                                                <span><?php echo theme_t('Читати блог', 'Read blog', 'Читать блог'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="mt-8 md:mt-10 flex justify-center md:justify-start">
                        <div class="inline-flex items-center gap-1 bg-[#F8F9FC] p-1 rounded-full">
                            <button aria-label="Previous" class="blogs-prev custom-arrow-btn w-9 h-9 rounded-full bg-transparent flex items-center justify-center transition-colors">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            </button>
                            <button aria-label="Next" class="blogs-next custom-arrow-btn w-9 h-9 rounded-full bg-transparent flex items-center justify-center transition-colors">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <?php
        } );
}
add_action( 'carbon_fields_register_fields', 'crb_attach_blocks' );
