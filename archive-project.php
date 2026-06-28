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

<div class="max-w-[1400px] w-full mx-auto px-6 lg:px-10 my-12 md:my-20">
    <div class="mb-3">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-0 tracking-tight" data-reveal="letters">
            <?php echo esc_html( $heading ); ?>
        </h1>
    </div>
    
    <span class="section-line mb-6" data-reveal="line"></span>
    
    <p class="text-gray-500 text-base md:text-lg max-w-xl leading-relaxed mb-12" data-reveal="rise">
        <?php echo esc_html( $subheading ); ?>
    </p>

    <?php if ( have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <?php 
            $i = 0;
            while ( have_posts() ) : the_post(); 
                $client = carbon_get_the_post_meta('project_client');
                $year = carbon_get_the_post_meta('project_year');
                $video = carbon_get_the_post_meta('project_video');
                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                $delay = ($i % 5) + 1;
            ?>
            <a href="<?php the_permalink(); ?>" class="card-hover group flex flex-col transition-all" data-reveal="rise" data-delay="<?php echo $delay; ?>">
                
                <!-- Media cover -->
                <div class="relative overflow-hidden bg-gray-100 aspect-video mb-4">
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
                
                <!-- Card info -->
                <div class="flex flex-col flex-grow">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">
                        <?php echo esc_html($client); ?> · <?php echo esc_html($year); ?>
                    </span>
                    <h2 class="font-bold mb-2 leading-snug text-xl">
                        <span class="text-wipe-hover inline-block"><?php the_title(); ?></span>
                    </h2>
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-3 flex-grow mb-4">
                        <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                    </p>
                    <span class="btn-ghost self-start mt-auto">
                        <?php echo theme_t('Детальніше', 'View project', 'Подробнее'); ?>
                        <svg width="10" height="8" viewBox="0 0 12 10" fill="none" aria-hidden="true"><path d="M1 5H11M6.5 1L11 5L6.5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
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
