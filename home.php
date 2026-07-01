<?php
get_header();

$title = theme_t(
    'Замітки та Думки', 
    'Thoughts & Writing', 
    'Заметки и Мысли'
);

$description = theme_t(
    'Огляди архітектури, розбори реального досвіду та практичні поради щодо розробки програмного забезпечення.', 
    'Architecture reviews, practical lessons from the field, and insights about clean software engineering.', 
    'Обзоры архитектуры, разборы практического опыта и полезные советы по разработке программного обеспечения.'
);
?>

<section class="breakout relative min-h-[50vh] md:min-h-[60vh] flex flex-col items-center justify-center pt-32 md:pt-48 pb-12 md:pb-20 text-center overflow-hidden mb-8 md:mb-12">
    <!-- Particles Container -->
    <div id="hero-particles" class="absolute inset-0 z-0 pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <h1 class="text-4xl md:text-6xl lg:text-[72px] font-[450] text-ag-black leading-[1.1] md:leading-[1.2] mb-6 tracking-tight" data-reveal="letters">
            <?php echo esc_html( $title ); ?>
        </h1>
        <p class="text-[16px] font-normal text-gray-500 leading-relaxed max-w-2xl mx-auto" data-reveal="rise">
            <?php echo esc_html( $description ); ?>
        </p>
    </div>
</section>

<div class="py-12 md:py-24 relative z-20">
    <div class="max-w-[1400px] mx-auto px-4 lg:px-6">
        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12 md:gap-y-16">
                <?php 
                $i = 0;
                while ( have_posts() ) : the_post(); 
                    $delay = ($i % 5) + 1;
                ?>
                <a href="<?php the_permalink(); ?>" class="block h-full flex flex-col group" data-reveal="rise" data-delay="<?php echo $delay; ?>">
                    <div class="w-full aspect-square bg-[#111111] rounded-[20px] overflow-hidden mb-6 flex items-center justify-center relative shadow-sm transition-transform duration-500 group-hover:scale-[1.02]">
                        <?php if ( get_the_post_thumbnail_url() ) : ?>
                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" loading="lazy" />
                        <?php else : ?>
                            <span class="text-white text-6xl font-bold opacity-80"><?php echo mb_substr(get_the_title(), 0, 1); ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-[16px] font-[450] tracking-tight text-ag-black mb-2 leading-snug group-hover:text-ag-accent transition-colors duration-300">
                        <?php the_title(); ?>
                    </h3>
                    <div class="text-[16px] font-[350] text-gray-500 leading-relaxed line-clamp-2 md:line-clamp-3 mb-8">
                        <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
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
                <?php 
                $i++;
                endwhile; 
                ?>
            </div>

            <?php
            the_posts_pagination( array(
                'prev_text' => theme_t('Попередня', 'Previous', 'Предыдущая'),
                'next_text' => theme_t('Наступна', 'Next', 'Следующая'),
                'class' => 'mt-20 flex justify-center gap-4 pagination-antigravity'
            ) );
            ?>

        <?php else : ?>
            <p class="text-gray-500 text-lg text-center"><?php echo theme_t('Нічого не знайдено', 'Nothing Found', 'Ничего не найдено'); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
