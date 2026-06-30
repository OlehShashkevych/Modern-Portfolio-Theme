<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class("flex flex-col min-h-screen bg-ag-bg text-ag-black font-sans selection:bg-ag-accent selection:text-white antialiased"); ?>>
<?php wp_body_open(); ?>

<!-- Page transition curtain -->
<div id="page-curtain" aria-hidden="true" class="bg-ag-bg">
    <span id="page-curtain-logo" class="text-ag-black"><?php echo esc_html( carbon_get_theme_option( 'shashkevych_logo_text' ) ?: 'Shashkevych' ); ?><span class="dot text-ag-accent">.</span>com</span>
</div>

<!-- Mobile full-screen menu -->
<div class="mobile-menu bg-ag-bg" id="mobile-menu" aria-hidden="true">
    <?php
    if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'list-none m-0 p-0 flex flex-col items-center gap-6 mt-16 text-2xl font-bold',
            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'fallback_cb'    => false,
        ) );
    }
    ?>
    
    <div class="flex items-center gap-4 mt-12 justify-center">
        <?php
        if ( function_exists('pll_the_languages') ) {
            $langs = pll_the_languages(array(
                'raw' => 1,
                'hide_if_empty' => 0,
            ));
            if ($langs) {
                foreach ($langs as $lang) {
                    $active_class = $lang['current_lang'] ? 'text-ag-accent' : 'text-gray-400';
                    echo '<a href="' . esc_url($lang['url']) . '" class="text-sm font-bold ' . $active_class . '">' . esc_html(strtoupper($lang['slug'])) . '</a>';
                }
            }
        }
        ?>
    </div>
</div>

<!-- Header Navigation -->
<header class="fixed top-0 left-0 right-0 z-[160] bg-ag-bg transition-transform duration-300 transform" id="main-header" style="height: 50px;">
    <div class="max-w-[1400px] mx-auto px-4 lg:px-6 h-full flex items-center justify-between relative z-10">
        
        <div class="flex items-center gap-6 md:gap-10">
            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="group font-black text-sm md:text-base tracking-tight uppercase">
                <span class="text-wipe-hover"><?php echo esc_html( carbon_get_theme_option( 'shashkevych_logo_text' ) ?: 'Shashkevych' ); ?><span class="text-ag-accent" style="-webkit-text-fill-color: initial;">.</span>com</span>
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'flex items-center gap-6',
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'fallback_cb'    => false,
                    ) );
                }
                ?>
            </nav>
        </div>

        <div class="flex items-center gap-4 md:gap-6">
            <!-- Language dropdown -->
            <div class="hidden md:block relative" id="lang-dd">
                <button class="flex items-center gap-1.5 text-[13px] font-semibold text-gray-700 hover:text-ag-black transition-colors lang-dropdown-toggle" aria-expanded="false" aria-haspopup="true">
                    <?php 
                    if ( function_exists('pll_current_language') ) {
                        echo strtoupper(pll_current_language('slug'));
                    } else {
                        echo 'LANG';
                    }
                    ?>
                    <svg width="8" height="5" viewBox="0 0 10 6" fill="none" aria-hidden="true" class="opacity-60 transition-transform">
                        <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="absolute top-[40px] right-0 bg-ag-bg shadow-[0_10px_40px_rgba(0,0,0,0.08)] rounded-2xl py-2 min-w-[100px] lang-menu-container" id="lang-menu" role="menu" style="z-index: 1000;">
                    <div class="relative z-10">
                    <?php
                    if ( function_exists('pll_the_languages') ) {
                        $langs = pll_the_languages(array(
                            'raw' => 1,
                            'hide_if_empty' => 0,
                        ));
                        if ($langs) {
                            foreach ($langs as $lang) {
                                $active_class = $lang['current_lang'] ? 'text-ag-accent bg-gray-50/50' : 'text-gray-500 hover:text-ag-black hover:bg-gray-50';
                                echo '<a href="' . esc_url($lang['url']) . '" class="flex items-center gap-2 px-5 py-2.5 text-[14px] font-[450] transition-colors ' . $active_class . '" role="menuitem">';
                                echo esc_html(strtoupper($lang['slug']));
                                if ($lang['current_lang']) {
                                    echo '<svg width="10" height="8" viewBox="0 0 10 8" fill="none" class="ml-auto"><path d="M1 4L3.5 6.5L9 1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                                }
                                echo '</a>';
                            }
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>

            <!-- Contact Button -->
            <a href="#contact" class="hidden md:inline-flex btn-animated">
                <div class="btn-animated-bg"></div>
                <div class="btn-animated-content">
                    <div class="btn-animated-text">
                        <span><?php echo theme_t('Зв\'язок', 'Contact', 'Связь'); ?></span>
                        <span><?php echo theme_t('Зв\'язок', 'Contact', 'Связь'); ?></span>
                    </div>
                    <div class="btn-animated-icon">
                        <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true" class="opacity-80"><path d="M1 7H13M8 2L13 7L8 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true" class="opacity-80"><path d="M1 7H13M8 2L13 7L8 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </a>

            <!-- Burger button (mobile only) -->
            <button class="md:hidden flex flex-col justify-center gap-1.5 w-6 h-6 text-ag-black z-[170] relative" id="burger-btn" aria-label="Toggle menu" aria-expanded="false">
                <span class="w-5 h-0.5 bg-current rounded-full transition-all origin-left"></span>
                <span class="w-5 h-0.5 bg-current rounded-full transition-all origin-left"></span>
            </button>
        </div>
    </div>
</header>

<!-- Main Content -->
<?php $main_padding = is_front_page() ? 'pt-[60px] pb-12' : 'pt-[60px] pb-6'; ?>
<main class="flex-grow max-w-[1400px] w-full mx-auto px-4 lg:px-6 <?php echo $main_padding; ?>">
