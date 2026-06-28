<?php
get_header();

$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>

<div class="max-w-3xl mx-auto my-12 md:my-20 px-6 lg:px-0">
    <!-- Back -->
    <?php $blog_url = get_option('page_for_posts') ? get_permalink(get_option('page_for_posts')) : home_url('/'); ?>
    <a href="<?php echo esc_url($blog_url); ?>"
      class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-[var(--accent)] transition-colors mb-10">
      <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true"><path d="M13 5H1M6 1L1 5L6 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <?php echo function_exists('pll__') ? pll__('All articles') : 'All articles'; ?>
    </a>

    <!-- Headline -->
    <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight mb-6 leading-[1.1]" data-reveal="letters">
      <?php the_title(); ?>
    </h1>

    <!-- Meta row -->
    <div class="flex items-center gap-4 text-xs font-bold tracking-widest uppercase text-gray-400 mb-10 pb-5 border-b border-gray-200" data-reveal="rise">
      <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
    </div>

    <!-- Cover media -->
    <?php if ( $thumbnail ) : ?>
      <div class="overflow-hidden mb-12 bg-gray-100" data-reveal="zoom">
        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-auto" />
      </div>
    <?php endif; ?>

    <!-- Content -->
    <article class="prose prose-lg max-w-none" data-reveal="rise">
      <?php the_content(); ?>
    </article>
</div>

<?php
get_footer();
