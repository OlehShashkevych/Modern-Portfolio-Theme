<?php
get_header();

$heading = theme_t(
    'Проекти', 
    'Projects', 
    'Проекты'
);

$subheading = theme_t(
    'Вибрані роботи, де я виступав ключовим учасником.', 
    'Selected works where I played a key role.', 
    'Избранные работы, в которых я был ключевым участником.'
);
?>

<div class="relative w-full min-h-[50vh] flex flex-col items-center justify-center overflow-hidden bg-ag-black pt-32 pb-24">
    <div class="relative z-10 max-w-[1400px] w-full mx-auto px-6 lg:px-10 text-center">
        <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tighter" data-reveal="rise">
            <?php echo esc_html( $heading ); ?>
        </h1>
        <p class="text-white/70 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-medium" data-reveal="rise" data-delay="2">
            <?php echo esc_html( $subheading ); ?>
        </p>
    </div>
</div>

<div class="bg-ag-white text-ag-black py-24 rounded-t-[3rem] -mt-12 relative z-20 shadow-[0_-20px_40px_rgba(0,0,0,0.3)]">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $i = 0;
                while ( have_posts() ) : the_post(); 
                    $client = carbon_get_the_post_meta('project_client');
                    $year = carbon_get_the_post_meta('project_year');
                    $video = carbon_get_the_post_meta('project_video');
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    $delay = ($i % 5) + 1;
                ?>
                <a href="<?php passage(); ?>" class="group block bg-ag-light rounded-[2rem] overflow-hidden hover:shadow-xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 flex flex-col" data-reveal="rise" data-delay="<?php echo $delay; ?>">
                    
                    <!-- Media cover -->
                    <div class="relative w-full aspect-video bg-gray-200 overflow-hidden">
                        <?php if ( $video ) : ?>
                        <iframe src="<?php echo esc_url($video); ?>" class="w-full h-full pointer-events-none group-hover:scale-105 transition-transform duration-700" allow="autoplay; encrypted-media" allowfullscreen loading="lazy"></iframe>
                        <?php elseif ( $thumbnail ) : ?>
                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy" />
                        <?php else : ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 group-hover:scale-105 transition-transform duration-700">
                            <span class="text-6xl font-black text-gray-300 select-none"><?php echo substr(get_the_title(), 0, 1); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Card info -->
                    <div class="p-8 flex flex-col flex-grow">
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">
                            <?php echo esc_html($client); ?> · <?php echo esc_html($year); ?>
                        </span>
                        <h2 class="text-2xl font-bold text-ag-black group-hover:text-ag-accent transition-colors mb-4 leading-snug tracking-tight">
                            <?php the_title(); ?>
                        </h2>
                        <p class="text-base text-gray-500 leading-relaxed line-clamp-3 mb-8 flex-grow">
                            <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                        </p>
                        <div class="mt-auto flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 group-hover:text-ag-black transition-colors">View Project</span>
                            <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center group-hover:bg-ag-black group-hover:border-ag-black group-hover:text-white transition-all shadow-sm">
                                <svg width="14" height="12" viewBox="0 0 14 12" fill="none" class="opacity-70 group-hover:opacity-100"><path d="M1 6H13M8 1L13 6L8 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
