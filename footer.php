</main>
<!-- End Main Content -->

<!-- Footer Container -->
<footer class="relative z-30 flex flex-col items-center pb-6 bg-white w-full">

    <!-- Top Block: Black Canvas -->
    <div id="contact" class="footer-cta-block relative w-[calc(100%-2rem)] md:w-[calc(100%-3rem)] bg-[#050505] text-white rounded-[2rem] overflow-hidden min-h-[calc(100svh-2rem)] md:min-h-[calc(100svh-3rem)] flex items-center transition-all duration-[1200ms] ease-[cubic-bezier(0.16,1,0.3,1)] scale-[0.95] js-footer-cta">
        <div id="footer-particles" class="absolute inset-0 z-0 pointer-events-none"></div>
        <div class="relative z-10 w-full max-w-[1400px] mx-auto px-6 lg:px-10 pointer-events-none">
            <div class="max-w-2xl pointer-events-auto">
                <h2 class="text-4xl md:text-5xl lg:text-[56px] font-[450] tracking-tight mb-4 leading-[1.1]">
                    <?php 
                        $contact_title = carbon_get_theme_option('shashkevych_contact_title') ?: 'Download Google Antigravity for Windows';
                        echo esc_html( function_exists('pll__') ? pll__($contact_title) : $contact_title ); 
                    ?>
                </h2>
                <p class="text-[18px] md:text-[20px] text-gray-400 mb-10 max-w-lg leading-relaxed font-[350]">
                    <?php 
                        $contact_text = carbon_get_theme_option('shashkevych_contact_text') ?: 'Achieve new heights';
                        echo esc_html( function_exists('pll__') ? pll__($contact_text) : $contact_text ); 
                    ?>
                </p>
                <?php $email = carbon_get_theme_option('shashkevych_email'); ?>
                <?php if ($email) : ?>
                <div class="flex gap-4">
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="js-footer-email-btn inline-flex items-center justify-center px-6 py-3 bg-white text-black text-[15px] font-semibold rounded-full hover:bg-gray-200 transition-colors pointer-events-auto">
                        <?php echo esc_html($email); ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bottom Block: White Main Footer -->
    <div class="w-full max-w-[1400px] mx-auto px-6 lg:px-10 mt-16 md:mt-24 bg-white relative z-20">
        
        <!-- Menus Row -->
        <div class="flex flex-col md:flex-row justify-between mb-16 md:mb-24 gap-12">
            <!-- Left heading -->
            <div class="w-full md:w-1/3">
                <h3 class="text-[28px] md:text-[32px] font-[450] tracking-tight text-ag-black leading-snug">
                    <?php echo theme_t('Досвід злету', 'Experience liftoff', 'Опыт взлета'); ?>
                </h3>
            </div>

            <!-- Menus -->
            <div class="w-full md:w-2/3 flex flex-col md:flex-row flex-wrap gap-x-16 gap-y-10 justify-start md:justify-end text-[15px] font-[450] text-ag-black">
                
                <div class="flex flex-col gap-4 <?php echo !has_nav_menu('footer_1') ? 'hidden md:flex' : ''; ?>">
                    <?php
                    if ( has_nav_menu('footer_1') ) {
                        wp_nav_menu( array(
                            'theme_location' => 'footer_1',
                            'container'      => false,
                            'menu_class'     => 'flex flex-col gap-4',
                            'fallback_cb'    => '__return_false',
                            'depth'          => 1,
                            'link_before'    => '<span class="transition-colors group-hover:text-ag-black">',
                            'link_after'     => '</span>',
                        ) );
                    }
                    ?>
                </div>

                <div class="flex flex-col gap-4 <?php echo !has_nav_menu('footer_2') ? 'hidden md:flex' : ''; ?>">
                    <?php
                    if ( has_nav_menu('footer_2') ) {
                        wp_nav_menu( array(
                            'theme_location' => 'footer_2',
                            'container'      => false,
                            'menu_class'     => 'flex flex-col gap-4',
                            'fallback_cb'    => '__return_false',
                            'depth'          => 1,
                            'link_before'    => '<span class="transition-colors group-hover:text-ag-black">',
                            'link_after'     => '</span>',
                        ) );
                    }
                    ?>
                </div>

                <!-- Socials (from Theme Settings) -->
                <div class="flex flex-col gap-4">
                    <?php 
                    $socials = carbon_get_theme_option('shashkevych_social_links'); 
                    if ( ! empty( $socials ) ) :
                        foreach ( $socials as $social ) :
                    ?>
                        <a href="<?php echo esc_url($social['url']); ?>" class="nav-link hover:text-ag-black transition-colors inline-block w-max" target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html($social['title']); ?>
                        </a>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </div>

            </div>
        </div>

        <!-- Massive Text -->
        <div class="w-full flex justify-center mb-10">
            <h2 class="text-[min(15vw,220px)] leading-[0.8] tracking-[-0.04em] font-[450] text-ag-black select-none text-center w-full">
                <?php echo esc_html( carbon_get_theme_option( 'shashkevych_logo_text' ) ?: 'Shashkevych' ); ?>
            </h2>
        </div>

        <!-- Bottom row -->
        <div class="flex flex-col-reverse md:flex-row justify-between items-center text-[13px] font-[450] text-gray-500 pt-6 pb-4 gap-4">
            <div class="text-[15px] font-[450] text-ag-black text-center md:text-left">
                <?php 
                    $footer_text = carbon_get_theme_option('shashkevych_footer_text') ?: '© ' . date('Y') . '.';
                    echo esc_html( function_exists('pll__') ? pll__($footer_text) : $footer_text ); 
                ?>
            </div>
            
            <div class="flex flex-wrap items-center justify-center gap-6 text-center md:text-right">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer_policies',
                    'container'      => false,
                    'menu_class'     => 'flex flex-wrap items-center gap-6 text-[13px] font-[450] text-gray-500',
                    'fallback_cb'    => '__return_false',
                    'depth'          => 1,
                    'link_before'    => '<span class="hover:text-ag-black transition-colors">',
                    'link_after'     => '</span>',
                ) );
                ?>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
