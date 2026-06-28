<?php
get_header();
?>

<div class="max-w-5xl mx-auto my-12 md:my-20">
    <div class="mb-10 md:mb-16">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 leading-[1.05] tracking-tight mb-4" data-reveal="letters">
            <?php post_type_archive_title(); ?>
        </h1>
        <?php if ( get_the_archive_description() ) : ?>
        <div class="text-lg text-gray-500 leading-relaxed max-w-2xl" data-reveal="rise">
            <?php echo wp_kses_post( wpautop( get_the_archive_description() ) ); ?>
        </div>
        <?php endif; ?>
    </div>

    <span class="section-line mb-10" data-reveal="line"></span>

    <?php if ( have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <?php 
            $i = 1;
            while ( have_posts() ) : the_post(); 
                $client = carbon_get_the_post_meta('project_client');
                $year = carbon_get_the_post_meta('project_year');
                $video = carbon_get_the_post_meta('project_video');
                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
            ?>
            <a href="<?php the_permalink(); ?>" class="card-hover group flex flex-col transition-all" data-reveal="rise" data-delay="<?php echo $i; ?>">
                <div class="relative aspect-video bg-gray-100 overflow-hidden mb-4">
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
                <div class="flex flex-col flex-grow">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">
                        <?php echo esc_html($client); ?> · <?php echo esc_html($year); ?>
                    </span>
                    <h3 class="text-xl font-bold mb-2 leading-snug">
                        <span class="text-wipe-hover inline-block"><?php the_title(); ?></span>
                    </h3>
                    <div class="text-sm text-gray-500 leading-relaxed line-clamp-2 flex-grow mb-4">
                        <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                    </div>
                    <span class="btn-ghost self-start text-[10px] mt-auto">
                        <?php echo function_exists('pll__') ? pll__('Read more') : 'Read more'; ?>
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
            'prev_text' => __( 'Previous', 'shashkevych' ),
            'next_text' => __( 'Next', 'shashkevych' ),
            'class' => 'mt-12 flex justify-center gap-2'
        ) );
        ?>

    <?php else : ?>
        <p class="text-gray-500 text-lg"><?php echo function_exists('pll__') ? pll__('Nothing Found') : 'Nothing Found'; ?></p>
    <?php endif; ?>
</div>

<?php
get_footer();
