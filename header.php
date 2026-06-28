<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
</head>
<body <?php body_class("flex flex-col min-h-screen bg-[#fdfbf6] text-gray-900"); ?>>
<?php wp_body_open(); ?>

<!-- Page transition curtain -->
<div id="page-curtain" aria-hidden="true">
    <span id="page-curtain-logo"><?php echo esc_html( carbon_get_theme_option( 'shashkevych_logo_text' ) ?: 'Shashkevych' ); ?><span class="dot">.</span>com</span>
</div>

<!-- Mobile full-screen menu -->
<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <?php
    if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'list-none m-0 p-0 flex flex-col',
            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'fallback_cb'    => false,
        ) );
    }
    ?>
    
    <div class="mobile-menu-lang">
        <?php
        if ( function_exists('pll_the_languages') ) {
            $langs = pll_the_languages(array(
                'raw' => 1,
                'hide_if_empty' => 0,
            ));
            if ($langs) {
                foreach ($langs as $lang) {
                    $active_class = $lang['current_lang'] ? 'active' : '';
                    echo '<a href="' . esc_url($lang['url']) . '" class="' . $active_class . '">' . esc_html(strtoupper($lang['slug'])) . '</a>';
                }
            }
        }
        ?>
    </div>
</div>

<!-- Header Navigation -->
<header class="border-b border-gray-200 bg-[#fdfbf6] sticky top-0 z-[160]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 h-16 flex items-center justify-between">
        
        <!-- Logo -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="group font-black text-base tracking-tight uppercase relative z-[200]">
            <span class="text-wipe-hover"><?php echo esc_html( carbon_get_theme_option( 'shashkevych_logo_text' ) ?: 'Shashkevych' ); ?><span class="text-[var(--accent)]" style="-webkit-text-fill-color: initial;">.</span>com</span>
        </a>

        <!-- Desktop Nav -->
        <nav class="desktop-nav flex items-center gap-8">
            <div class="flex items-center gap-8 font-bold uppercase tracking-widest text-xs">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'flex items-center gap-8',
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'fallback_cb'    => false,
                    ) );
                }
                ?>
            </div>

            <span class="h-4 w-px bg-gray-200"></span>

            <!-- Language dropdown -->
            <div class="lang-dropdown" id="lang-dd">
                <button class="lang-dropdown-toggle" aria-expanded="false" aria-haspopup="true">
                    <?php 
                    if ( function_exists('pll_current_language') ) {
                        echo strtoupper(pll_current_language('slug'));
                    } else {
                        echo 'LANG';
                    }
                    ?>
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" aria-hidden="true">
                        <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="lang-dropdown-menu" role="menu">
                    <?php
                    if ( function_exists('pll_the_languages') ) {
                        $langs = pll_the_languages(array(
                            'raw' => 1,
                            'hide_if_empty' => 0,
                        ));
                        if ($langs) {
                            foreach ($langs as $lang) {
                                $active_class = $lang['current_lang'] ? 'active' : '';
                                echo '<a href="' . esc_url($lang['url']) . '" class="' . $active_class . '" role="menuitem">';
                                echo esc_html(strtoupper($lang['slug']));
                                if ( $lang['current_lang'] ) {
                                    echo '<svg width="10" height="8" viewBox="0 0 10 8" fill="none" aria-hidden="true" class="ml-2 inline-block"><path d="M1 4L3.5 6.5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                                }
                                echo '</a>';
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </nav>

        <!-- Burger button (mobile only) -->
        <button class="burger-btn" id="burger-btn" aria-label="Toggle menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<!-- Main Content -->
<main class="flex-grow max-w-[1400px] w-full mx-auto px-6 lg:px-10 py-12 md:py-20">
