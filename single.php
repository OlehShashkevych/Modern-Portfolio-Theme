<?php
get_header();

$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>

<div class="relative w-full pt-32 pb-24 bg-ag-black">
    <div class="max-w-[1000px] mx-auto px-6 lg:px-10">
        <!-- Back -->
        <?php $blog_url = get_option('page_for_posts') ? get_permalink(get_option('page_for_posts')) : home_url('/'); ?>
        <a href="<?php echo esc_url($blog_url); ?>"
          class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-white transition-colors mb-12">
          <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true"><path d="M13 5H1M6 1L1 5L6 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <?php echo theme_t('Всі статті', 'All articles', 'Все статьи'); ?>
        </a>

        <!-- Headline -->
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white tracking-tighter mb-8 leading-[1.1]" data-reveal="rise">
          <?php the_title(); ?>
        </h1>

        <!-- Meta row -->
        <div class="flex items-center gap-4 text-sm font-semibold tracking-wider uppercase text-gray-500" data-reveal="rise" data-delay="2">
          <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
        </div>
    </div>
</div>

<div class="bg-ag-white text-ag-black py-24 rounded-t-[3rem] -mt-12 relative z-20 shadow-[0_-20px_40px_rgba(0,0,0,0.3)]">
    <div class="max-w-[1000px] mx-auto px-6 lg:px-10">
        <!-- Cover media -->
        <?php if ( $thumbnail ) : ?>
          <div class="overflow-hidden mb-16 rounded-[2rem] shadow-2xl" data-reveal="rise">
            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-auto object-cover" />
          </div>
        <?php endif; ?>

        <!-- Content -->
        <article class="prose prose-lg md:prose-xl max-w-none" data-reveal="rise">
          <?php the_content(); ?>
        </article>
    </div>
</div>

<?php
get_footer();
