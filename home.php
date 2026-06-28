<?php
get_header();

$page_for_posts = get_option( 'page_for_posts' );
$title = $page_for_posts ? get_the_title( $page_for_posts ) : __( 'Blog', 'shashkevych' );
$description = $page_for_posts ? get_post_field( 'post_excerpt', $page_for_posts ) : '';
?>

<div class="max-w-4xl mx-auto my-12 md:my-20">
    <div class="mb-10 md:mb-16">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 leading-[1.05] tracking-tight mb-4" data-reveal="letters">
            <?php echo esc_html( $title ); ?>
        </h1>
        <?php if ( $description ) : ?>
        <div class="text-lg text-gray-500 leading-relaxed max-w-2xl" data-reveal="rise">
            <?php echo wp_kses_post( wpautop( $description ) ); ?>
        </div>
        <?php endif; ?>
    </div>

    <span class="section-line mb-10" data-reveal="line"></span>

    <?php if ( have_posts() ) : ?>
        <div class="flex flex-col border-t border-gray-200">
            <?php 
            $i = 1;
            while ( have_posts() ) : the_post(); 
            ?>
            <article class="py-8 md:py-10 border-b border-gray-200 group flex flex-col md:flex-row gap-6 md:gap-12 transition-colors hover:bg-white" data-reveal="rise" data-delay="<?php echo $i; ?>">
                
                <div class="w-full md:w-1/4 shrink-0">
                    <time datetime="<?php echo get_the_date('c'); ?>" class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <?php echo get_the_date(); ?>
                    </time>
                </div>
                
                <div class="flex-1">
                    <a href="<?php the_permalink(); ?>" class="block">
                        <h2 class="text-2xl font-bold mb-4 leading-snug text-gray-900 group-hover:text-[var(--accent)] transition-colors">
                            <?php the_title(); ?>
                        </h2>
                        <div class="text-gray-500 leading-relaxed mb-4 line-clamp-3">
                            <?php echo wp_trim_words( get_the_excerpt(), 30 ); ?>
                        </div>
                        <span class="link-hover text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover:text-gray-900 transition-colors">
                            <?php echo theme_t('Читати далі', 'Read more', 'Читать далее'); ?>
                        </span>
                    </a>
                </div>
            </article>
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
        <p class="text-gray-500 text-lg"><?php echo theme_t('Нічого не знайдено', 'Nothing Found', 'Ничего не найдено'); ?></p>
    <?php endif; ?>
</div>

<?php
get_footer();
