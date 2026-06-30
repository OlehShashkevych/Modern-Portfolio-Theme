</main>
<!-- End Main Content -->

<!-- Footer -->
<footer class="bg-ag-white text-ag-black pt-20 pb-10 relative z-30 overflow-hidden rounded-t-[3rem] -mt-12 shadow-[0_-20px_40px_rgba(0,0,0,0.1)]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10">
        
        <!-- Top links row -->
        <div class="flex flex-col md:flex-row justify-between mb-24 text-sm font-semibold tracking-wide">
            <div class="mb-8 md:mb-0 max-w-sm">
                <h3 class="text-xl font-bold mb-4">
                    <?php 
                        $contact_title = carbon_get_theme_option('shashkevych_contact_title') ?: 'Get in touch';
                        echo esc_html( function_exists('pll__') ? pll__($contact_title) : $contact_title ); 
                    ?>
                </h3>
                <p class="text-gray-500 leading-relaxed">
                    <?php 
                        $contact_text = carbon_get_theme_option('shashkevych_contact_text') ?: 'Feel free to contact me';
                        echo esc_html( function_exists('pll__') ? pll__($contact_text) : $contact_text ); 
                    ?>
                </p>
            </div>
            
            <div class="flex flex-wrap gap-x-16 gap-y-6">
                <div class="flex flex-col gap-3">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Contact</span>
                    <?php $email = carbon_get_theme_option('shashkevych_email'); ?>
                    <?php if ($email) : ?>
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="hover:text-ag-accent transition-colors">
                        <?php echo esc_html($email); ?>
                    </a>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-3">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Social</span>
                    <?php 
                    $socials = carbon_get_theme_option('shashkevych_social_links'); 
                    if ( ! empty( $socials ) ) :
                        foreach ( $socials as $social ) :
                    ?>
                        <a href="<?php echo esc_url($social['url']); ?>" class="hover:text-ag-accent transition-colors" target="_blank" rel="noopener noreferrer">
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
        <div class="w-full flex justify-center mb-16 overflow-hidden">
            <h2 class="text-[12vw] font-black tracking-tighter leading-none text-ag-black select-none text-center w-full">
                <?php echo esc_html( carbon_get_theme_option( 'shashkevych_logo_text' ) ?: 'Shashkevych' ); ?>
            </h2>
        </div>

        <!-- Bottom row -->
        <div class="flex flex-col md:flex-row justify-between items-center text-xs font-semibold text-gray-500 uppercase tracking-widest pt-8 border-t border-gray-200">
            <div>
                <?php 
                    $footer_text = carbon_get_theme_option('shashkevych_footer_text') ?: '© ' . date('Y') . '.';
                    echo esc_html( function_exists('pll__') ? pll__($footer_text) : $footer_text ); 
                ?>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
