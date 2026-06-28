</main>
<!-- End Main Content -->

<!-- Footer -->
<footer class="text-gray-400 text-xs mt-auto">

    <!-- Contact block -->
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 pb-10">
        <section id="contact" class="bg-gray-900 p-10 md:p-16" data-reveal="zoom">
            <div class="max-w-xl mb-10">
                <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-4 text-white" data-reveal="letters">
                    <?php 
                        $contact_title = carbon_get_theme_option('shashkevych_contact_title') ?: 'Get in touch';
                        echo esc_html( function_exists('pll__') ? pll__($contact_title) : $contact_title ); 
                    ?>
                </h2>
                <p class="text-gray-400 text-base leading-relaxed">
                    <?php 
                        $contact_text = carbon_get_theme_option('shashkevych_contact_text') ?: 'Feel free to contact me';
                        echo esc_html( function_exists('pll__') ? pll__($contact_text) : $contact_text ); 
                    ?>
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-4">
                <?php $email = carbon_get_theme_option('shashkevych_email'); ?>
                <?php if ($email) : ?>
                <a href="mailto:<?php echo esc_attr($email); ?>" class="btn-inverted">
                    <?php echo esc_html($email); ?>
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7H12M7 2L12 7L7 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <?php endif; ?>

                <div class="flex flex-wrap gap-5 text-xs font-bold uppercase tracking-widest text-gray-500">
                    <?php 
                    $socials = carbon_get_theme_option('shashkevych_social_links'); 
                    if ( ! empty( $socials ) ) :
                        foreach ( $socials as $social ) :
                    ?>
                        <a href="<?php echo esc_url($social['url']); ?>" class="nav-link-inverse hover:text-white transition-colors flex items-center gap-1.5 pb-1" target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html($social['title']); ?>
                        </a>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </section>
    </div>

    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-8 text-center md:text-left">
        <div>
            <?php 
                $footer_text = carbon_get_theme_option('shashkevych_footer_text') ?: '© ' . date('Y') . '.';
                echo esc_html( function_exists('pll__') ? pll__($footer_text) : $footer_text ); 
            ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
