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

<section class="breakout relative min-h-[50vh] md:min-h-[60vh] flex flex-col items-center justify-center pt-32 md:pt-48 pb-12 md:pb-20 text-center overflow-hidden mb-8 md:mb-12">
    <!-- Particles Container -->
    <div id="hero-particles" class="absolute inset-0 z-0 pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <h1 class="text-4xl md:text-6xl lg:text-[72px] font-[450] text-ag-black leading-[1.1] md:leading-[1.2] mb-6 tracking-tight" data-reveal="letters">
            <?php echo esc_html( $heading ); ?>
        </h1>
        <p class="text-[16px] font-normal text-gray-500 leading-relaxed max-w-2xl mx-auto" data-reveal="rise" data-delay="1">
            <?php echo esc_html( $subheading ); ?>
        </p>
    </div>
</section>

<div class="py-12 md:py-24 relative z-20">
    <div class="max-w-[1400px] mx-auto px-4 lg:px-6">
        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-16">
                <?php 
                $i = 0;
                while ( have_posts() ) : the_post(); 
                    $client = carbon_get_the_post_meta('project_client');
                    $year = carbon_get_the_post_meta('project_year');
                    $video = carbon_get_the_post_meta('project_video');
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    $delay = ($i % 5) + 1;
                ?>
                <div class="group flex flex-col" data-reveal="rise" data-delay="<?php echo $delay; ?>">
                    <div class="project-image-wrapper relative aspect-[16/10] bg-ag-light rounded-[32px] overflow-hidden cursor-pointer border border-black mb-6" data-project-url="<?php the_permalink(); ?>">
                        <?php if ( $video ) : ?>
                        <iframe src="<?php echo esc_url($video); ?>" class="w-full h-full pointer-events-none object-cover" allow="autoplay; encrypted-media" allowfullscreen loading="lazy"></iframe>
                        <?php elseif ( $thumbnail ) : ?>
                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" loading="lazy" />
                        <?php else : ?>
                        <div class="w-full h-full flex items-center justify-center bg-ag-light">
                            <span class="text-6xl font-black text-gray-200 select-none"><?php echo substr(get_the_title(), 0, 1); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Custom Cursor Button -->
                        <div class="watch-case-btn absolute top-0 left-0 w-[150px] h-[52px] bg-white text-ag-black font-semibold text-[15px] flex items-center justify-center gap-2 rounded-full pointer-events-none z-20 transition-transform duration-300 shadow-lg scale-0 opacity-0" style="margin-top: -26px; margin-left: -75px;">
                            <svg width="8" height="12" viewBox="0 0 8 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 1L7 7L1 13"/></svg>
                            <?php echo theme_t('Дивитись кейс', 'Watch case', 'Смотреть кейс'); ?>
                        </div>
                    </div>
                    
                    <div class="flex flex-col flex-grow">
                        <span class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-3 block">
                            <?php echo esc_html($client); ?> · <?php echo esc_html($year); ?>
                        </span>
                        <h3 class="text-[16px] font-[450] tracking-tight mb-2 leading-snug">
                            <a href="<?php the_permalink(); ?>" class="inline-block text-ag-black hover:text-ag-accent transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <div class="text-[16px] font-[350] text-gray-500 leading-relaxed line-clamp-2 md:line-clamp-3 mb-6">
                            <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="project-link btn-animated btn-animated-ghost self-start mt-auto">
                            <div class="btn-animated-bg"></div>
                            <div class="btn-animated-content">
                                <div class="btn-animated-text">
                                    <span><?php echo theme_t('Дивитись кейс', 'View case', 'Смотреть проект'); ?></span>
                                    <span><?php echo theme_t('Дивитись кейс', 'View case', 'Смотреть проект'); ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
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
