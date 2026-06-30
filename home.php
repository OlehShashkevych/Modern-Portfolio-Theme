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

<div class="relative w-full min-h-[70vh] flex flex-col items-center justify-center overflow-hidden bg-ag-black pt-32 pb-20">
    <!-- Three.js Canvas Container -->
    <div id="canvas-container" class="absolute inset-0 z-0 opacity-80 pointer-events-none"></div>
    
    <div class="relative z-10 max-w-[1400px] w-full mx-auto px-6 lg:px-10 text-center">
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6 tracking-tighter" data-reveal="rise">
            <?php echo esc_html( $title ); ?>
        </h1>
        <p class="text-white/70 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed mb-12 font-medium" data-reveal="rise" data-delay="2">
            <?php echo esc_html( $description ); ?>
        </p>
    </div>
</div>

<div class="bg-ag-white text-ag-black py-24 rounded-t-[3rem] -mt-12 relative z-20 shadow-[0_-20px_40px_rgba(0,0,0,0.3)]">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $i = 0;
                while ( have_posts() ) : the_post(); 
                    $delay = ($i % 5) + 1;
                ?>
                <a href="<?php the_permalink(); ?>" class="group block bg-ag-light rounded-[2rem] p-8 hover:bg-gray-100 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 border border-gray-100" data-reveal="rise" data-delay="<?php echo $delay; ?>">
                    <h2 class="text-2xl font-bold text-ag-black group-hover:text-ag-accent transition-colors mb-4 leading-snug tracking-tight">
                        <?php the_title(); ?>
                    </h2>
                    <p class="text-base text-gray-500 leading-relaxed line-clamp-3">
                        <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                    </p>
                    <div class="mt-8 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-400 group-hover:text-ag-black transition-colors">Read Article</span>
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center group-hover:bg-ag-black group-hover:border-ag-black group-hover:text-white transition-all shadow-sm">
                            <svg width="14" height="12" viewBox="0 0 14 12" fill="none" class="opacity-70 group-hover:opacity-100"><path d="M1 6H13M8 1L13 6L8 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
