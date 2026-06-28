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

<div class="max-w-4xl mx-auto my-12 md:my-20">
    <div class="mb-3">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-0 tracking-tight" data-reveal="letters">
            <?php echo esc_html( $title ); ?>
        </h1>
    </div>
    
    <span class="section-line mb-6" data-reveal="line"></span>
    
    <p class="text-gray-500 text-base md:text-lg max-w-xl leading-relaxed mb-12" data-reveal="rise">
        <?php echo esc_html( $description ); ?>
    </p>

    <?php if ( have_posts() ) : ?>
        <div class="divide-y divide-gray-200">
            <?php 
            $i = 0;
            while ( have_posts() ) : the_post(); 
                $delay = ($i % 5) + 1;
            ?>
            <a href="<?php the_permalink(); ?>" class="group flex flex-col md:flex-row md:items-start justify-between gap-4 py-7 hover:bg-white transition-colors duration-500 px-2 -mx-2" data-reveal="rise" data-delay="<?php echo $delay; ?>">
                
                <div class="flex-grow">
                    <h2 class="text-base font-bold text-gray-900 group-hover:text-[var(--accent)] transition-colors mb-2 leading-snug">
                        <?php the_title(); ?>
                    </h2>
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">
                        <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                    </p>
                </div>
                
                <div class="flex items-center gap-3 shrink-0">
                    <svg width="12" height="10" viewBox="0 0 12 10" fill="none" aria-hidden="true" class="opacity-0 group-hover:opacity-100 transition-opacity text-[var(--accent)]"><path d="M1 5H11M6.5 1L11 5L6.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
            'class' => 'mt-12 flex justify-center gap-2'
        ) );
        ?>

    <?php else : ?>
        <p class="text-gray-500 text-lg"><?php echo theme_t('Нічого не знайдено', 'Nothing Found', 'Ничего не найдено'); ?></p>
    <?php endif; ?>
</div>

<?php
get_footer();
