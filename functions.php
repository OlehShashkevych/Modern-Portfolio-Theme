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
        'footer'  => __( 'Footer Menu', 'shashkevych' ),
    ) );
}
add_action( 'after_setup_theme', 'shashkevych_setup' );

// Filter nav menu items to add classes
function shashkevych_nav_menu_link_attributes( $atts, $item, $args ) {
    if ( $args->theme_location === 'primary' ) {
        $atts['class'] = 'nav-link text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-colors';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'shashkevych_nav_menu_link_attributes', 10, 3 );

// 2. Enqueue Scripts and Styles
function shashkevych_enqueue_scripts() {
    wp_enqueue_style( 'shashkevych-style', get_template_directory_uri() . '/dist/style.css', array(), '1.0.0' );
    wp_enqueue_script( 'shashkevych-script', get_template_directory_uri() . '/dist/main.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'shashkevych_enqueue_scripts' );

function shashkevych_enqueue_block_editor_assets() {
    wp_enqueue_style( 'shashkevych-editor-style', get_template_directory_uri() . '/dist/style.css', array(), '1.0.0' );
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
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
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
            <section class="mb-24 md:mb-32 pt-8 flex flex-col md:flex-row items-center gap-12 md:gap-16">
                <div class="flex-1 max-w-4xl">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 leading-[1.05] mb-7 tracking-tight" data-reveal="letters">
                        <?php echo esc_html( $fields['heading'] ); ?>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-500 leading-relaxed max-w-2xl mb-10" data-reveal="rise">
                        <?php echo esc_html( $fields['subheading'] ); ?>
                    </p>
                    <div class="flex flex-wrap gap-4" data-reveal="rise" data-delay="1">
                        <?php if ( $fields['btn_primary_text'] && $fields['btn_primary_url'] ) : ?>
                        <a href="<?php echo esc_url( $fields['btn_primary_url'] ); ?>" class="btn-primary">
                            <?php echo esc_html( $fields['btn_primary_text'] ); ?>
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7H12M7 2L12 7L7 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <?php endif; ?>
                        <?php if ( $fields['btn_secondary_text'] && $fields['btn_secondary_url'] ) : ?>
                        <a href="<?php echo esc_url( $fields['btn_secondary_url'] ); ?>" class="btn-secondary">
                            <?php echo esc_html( $fields['btn_secondary_text'] ); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ( $fields['image'] ) : ?>
                <div class="w-56 h-56 md:w-72 md:h-72 lg:w-96 lg:h-96 shrink-0 relative" data-reveal="zoom">
                    <div class="absolute inset-0 bg-gray-200 translate-x-3 translate-y-3 opacity-50"></div>
                    <img src="<?php echo esc_url( $fields['image'] ); ?>" alt="" class="w-full h-full object-cover relative z-10" />
                </div>
                <?php endif; ?>
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
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            $projects = new WP_Query(array(
                'post_type' => 'project',
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            ?>
            <section class="mb-24 md:mb-32">
                <div class="flex items-end justify-between mb-3">
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight text-gray-900" data-reveal="letters"><?php echo esc_html($fields['title']); ?></h2>
                    <?php if ( $fields['link_text'] && $fields['link_url'] ) : ?>
                    <a href="<?php echo esc_url($fields['link_url']); ?>" class="btn-ghost" data-reveal="left">
                        <?php echo esc_html($fields['link_text']); ?>
                        <svg width="12" height="10" viewBox="0 0 12 10" fill="none" aria-hidden="true"><path d="M1 5H11M6.5 1L11 5L6.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
                <span class="section-line mb-10" data-reveal="line"></span>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    <?php 
                    $i = 1;
                    if ( $projects->have_posts() ) : 
                        while ( $projects->have_posts() ) : $projects->the_post(); 
                            $client = carbon_get_the_post_meta('project_client');
                            $year = carbon_get_the_post_meta('project_year');
                            $video = carbon_get_the_post_meta('project_video');
                            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    ?>
                    <a href="<?php the_permalink(); ?>" class="card-hover group flex flex-col transition-all" data-reveal="rise" data-delay="<?php echo $i; ?>">
                        <div class="relative aspect-video bg-gray-100 overflow-hidden mb-4">
                            <?php if ( $video ) : ?>
                            <iframe src="<?php echo esc_url($video); ?>" class="w-full h-full pointer-events-none grayscale group-hover:grayscale-0 transition-[filter] duration-500" allow="autoplay; encrypted-media" allowfullscreen loading="lazy"></iframe>
                            <?php elseif ( $thumbnail ) : ?>
                            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-[filter] duration-500" loading="lazy" />
                            <?php else : ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-50 grayscale group-hover:grayscale-0 transition-[filter] duration-500">
                                <span class="text-6xl font-black text-gray-200 select-none"><?php echo substr(get_the_title(), 0, 1); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col flex-grow">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">
                                <?php echo esc_html($client); ?> · <?php echo esc_html($year); ?>
                            </span>
                            <h3 class="text-xl font-bold mb-2 leading-snug">
                                <span class="text-wipe-hover inline-block"><?php the_title(); ?></span>
                            </h3>
                            <div class="text-sm text-gray-500 leading-relaxed line-clamp-2 flex-grow mb-4">
                                <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                            </div>
                            <span class="btn-ghost self-start text-[10px] mt-auto">
                                <?php echo theme_t('Читати далі', 'Read more', 'Читать далее'); ?>
                                <svg width="10" height="8" viewBox="0 0 12 10" fill="none" aria-hidden="true"><path d="M1 5H11M6.5 1L11 5L6.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
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
            <section class="mb-24 md:mb-32">
                <div class="mb-3">
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight text-gray-900" data-reveal="letters"><?php echo esc_html($fields['title']); ?></h2>
                </div>
                <span class="section-line mb-6" data-reveal="line"></span>
                <p class="text-sm text-gray-400 mb-10" data-reveal="rise"><?php echo esc_html($fields['subtitle']); ?></p>
                
                <div class="overflow-hidden w-full relative" data-reveal="rise">
                    <div class="absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-[#fdfbf6] to-transparent z-10 pointer-events-none"></div>
                    <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-[#fdfbf6] to-transparent z-10 pointer-events-none"></div>
                    
                    <div class="flex w-max animate-marquee hover:[animation-play-state:paused] items-center">
                        <?php foreach ( $logos as $logo ) : ?>
                        <a href="<?php echo esc_url($logo['url'] ?: '#'); ?>" <?php if($logo['url']) echo 'target="_blank" rel="noopener noreferrer"'; ?> class="flex items-center justify-center mx-6 md:mx-10 group opacity-80 hover:opacity-100 transition-all duration-500" title="<?php echo esc_attr($logo['name']); ?>">
                            <?php if ( $logo['image'] ) : ?>
                            <div class="relative h-8">
                                <img src="<?php echo esc_url($logo['image']); ?>" alt="<?php echo esc_attr($logo['name']); ?>" class="h-8 object-contain max-w-[120px] grayscale" />
                                <img src="<?php echo esc_url($logo['image']); ?>" aria-hidden="true" alt="" class="h-8 object-contain max-w-[120px] absolute top-0 left-0 [clip-path:inset(0_100%_0_0)] group-hover:[clip-path:inset(0_0_0_0)] transition-[clip-path] duration-500 ease-in-out" />
                            </div>
                            <?php else : ?>
                            <span class="text-sm font-black uppercase tracking-widest text-wipe-hover">
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
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            $posts = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            ?>
            <section class="mb-24 md:mb-32">
                <div class="flex items-end justify-between mb-3">
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight text-gray-900" data-reveal="letters"><?php echo esc_html($fields['title']); ?></h2>
                    <?php if ( $fields['link_text'] && $fields['link_url'] ) : ?>
                    <a href="<?php echo esc_url($fields['link_url']); ?>" class="btn-ghost" data-reveal="left">
                        <?php echo esc_html($fields['link_text']); ?>
                        <svg width="12" height="10" viewBox="0 0 12 10" fill="none" aria-hidden="true"><path d="M1 5H11M6.5 1L11 5L6.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
                <span class="section-line mb-10" data-reveal="line"></span>

                <div class="grid grid-cols-1 md:grid-cols-3">
                    <?php 
                    $i = 1;
                    if ( $posts->have_posts() ) : 
                        while ( $posts->have_posts() ) : $posts->the_post(); 
                    ?>
                    <a href="<?php the_permalink(); ?>" class="card-hover group block p-6 transition-colors border-b md:border-b-0 md:border-r border-gray-200 md:[&:nth-child(3n)]:border-r-0 last:border-0 md:last:border-r-0" data-reveal="rise" data-delay="<?php echo $i; ?>">
                        <h3 class="text-sm font-bold text-gray-900 group-hover:text-[var(--accent)] transition-colors mb-2 leading-snug">
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
            Field::make( 'textarea', 'subheading', __( 'Subheading' ) ),
            Field::make( 'image', 'image', __( 'Profile Image' ) )->set_value_type( 'url' ),
        ) )
        ->set_inner_blocks( true )
        ->set_inner_blocks_template( array(
            array( 'core/paragraph', array( 'placeholder' => 'Write about yourself here...' ) ),
        ) )
        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
            ?>
            <div class="max-w-5xl mx-auto my-12">
                <div class="flex flex-col md:flex-row gap-12 lg:gap-20 items-start">
                    <div class="flex-1">
                        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 tracking-tight" data-reveal="letters">
                            <?php echo esc_html( $fields['heading'] ); ?>
                        </h1>
                        <span class="section-line mb-8" data-reveal="line"></span>
                        <p class="text-gray-500 text-xl font-medium leading-relaxed mb-5" data-reveal="rise">
                            <?php echo esc_html( $fields['subheading'] ); ?>
                        </p>

                        <article class="prose prose-lg max-w-none" data-reveal="rise">
                            <?php echo $inner_blocks; ?>
                        </article>
                    </div>

                    <?php if ( $fields['image'] ) : ?>
                    <div class="w-full md:w-1/3 lg:w-[400px] shrink-0 sticky top-32" data-reveal="zoom">
                        <img src="<?php echo esc_url( $fields['image'] ); ?>" alt="<?php echo esc_attr( $fields['heading'] ); ?>" class="w-full h-auto object-cover" />
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        } );
}
add_action( 'carbon_fields_register_fields', 'crb_attach_blocks' );
