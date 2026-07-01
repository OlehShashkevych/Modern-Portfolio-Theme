<?php
get_header();

$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>

<section class="breakout relative min-h-[50vh] md:min-h-[60vh] flex flex-col items-center justify-center pt-32 md:pt-48 pb-12 md:pb-20 text-center overflow-hidden mb-8 md:mb-12">
    <!-- Particles Container -->
    <div id="hero-particles" class="absolute inset-0 z-0 pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <!-- Back -->
        <?php $blog_url = get_option('page_for_posts') ? get_permalink(get_option('page_for_posts')) : home_url('/'); ?>
        <a href="<?php echo esc_url($blog_url); ?>"
          class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-ag-black transition-colors mb-12" data-reveal="rise">
          <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true"><path d="M13 5H1M6 1L1 5L6 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <?php echo theme_t('Всі статті', 'All articles', 'Все статьи'); ?>
        </a>

        <!-- Headline -->
        <h1 class="text-4xl md:text-6xl lg:text-[72px] font-[450] text-ag-black leading-[1.1] md:leading-[1.2] mb-6 tracking-tight" data-reveal="letters">
          <?php the_title(); ?>
        </h1>

        <!-- Meta row -->
        <div class="flex items-center justify-center gap-4 text-sm font-semibold tracking-wider uppercase text-gray-500" data-reveal="rise">
          <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
        </div>
    </div>
</section>

<div class="py-12 md:py-24 relative z-20">
    <div class="max-w-[1000px] mx-auto px-6 lg:px-10">
        <!-- Cover media -->
        <?php if ( $thumbnail ) : ?>
          <div class="overflow-hidden mb-16 rounded-[32px] w-full" data-reveal="rise">
            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-auto object-cover" />
          </div>
        <?php endif; ?>

        <!-- Content -->
        <article class="prose prose-lg md:prose-xl max-w-none prose-headings:font-[450] prose-strong:font-[450] prose-a:text-ag-accent prose-a:no-underline hover:prose-a:underline" data-reveal="rise">
          <?php the_content(); ?>
        </article>
    </div>
</div>

<?php
get_footer();
