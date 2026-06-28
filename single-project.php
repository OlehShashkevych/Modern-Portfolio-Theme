<?php
get_header();

$client = carbon_get_the_post_meta('project_client');
$role = carbon_get_the_post_meta('project_role');
$year = carbon_get_the_post_meta('project_year');
$link = carbon_get_the_post_meta('project_link');
$video = carbon_get_the_post_meta('project_video');
$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>

<div class="max-w-4xl mx-auto my-12 md:my-20 px-6 lg:px-0">

    <!-- Back -->
    <a href="<?php echo get_post_type_archive_link('project'); ?>"
      class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-[var(--accent)] transition-colors mb-10">
      <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true"><path d="M13 5H1M6 1L1 5L6 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <?php echo theme_t('Назад до проектів', 'Back to projects', 'Назад к проектам'); ?>
    </a>

    <!-- Cover media -->
    <?php if ( $video || $thumbnail ) : ?>
      <div class="overflow-hidden mb-10 bg-gray-100 aspect-video" data-reveal="zoom">
        <?php if ( $video ) : ?>
          <iframe
            src="<?php echo esc_url($video); ?>"
            class="w-full h-full"
            allow="autoplay; encrypted-media"
            allowfullscreen
          ></iframe>
        <?php else : ?>
          <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Headline -->
    <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight mb-5 leading-[1.05]" data-reveal="letters">
      <?php the_title(); ?>
    </h1>

    <!-- Meta row -->
    <div class="flex flex-wrap gap-x-8 gap-y-4 mb-3 border-t border-gray-200 pt-5" data-reveal="rise">
      <?php if ( $client ) : ?>
      <div>
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-0.5"><?php echo theme_t('Клієнт', 'Client', 'Клиент'); ?></span>
        <span class="text-sm font-bold text-gray-900"><?php echo esc_html($client); ?></span>
      </div>
      <?php endif; ?>
      
      <?php if ( $role ) : ?>
      <div>
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-0.5"><?php echo theme_t('Роль', 'Role', 'Роль'); ?></span>
        <span class="text-sm font-bold text-gray-900"><?php echo esc_html($role); ?></span>
      </div>
      <?php endif; ?>
      
      <?php if ( $year ) : ?>
      <div>
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-0.5"><?php echo theme_t('Рік', 'Year', 'Год'); ?></span>
        <span class="text-sm font-bold text-gray-900"><?php echo esc_html($year); ?></span>
      </div>
      <?php endif; ?>
      
      <?php if ( $link ) : ?>
        <div>
          <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-0.5">URL</span>
          <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center gap-1 text-sm font-bold text-[var(--accent)] hover:text-[var(--accent-dark)] transition-colors">
            <?php echo theme_t('Відвідати проект', 'Visit project', 'Посетить проект'); ?>
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none" aria-hidden="true"><path d="M2 8L8 2M8 2H4M8 2V6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      <?php endif; ?>
    </div>
    <span class="section-line mb-10" data-reveal="line"></span>

    <!-- Summary -->
    <?php if ( has_excerpt() ) : ?>
    <p class="text-gray-500 text-lg leading-relaxed italic border-l-2 border-[var(--accent)] pl-5 mb-12" data-reveal="left">
      <?php echo get_the_excerpt(); ?>
    </p>
    <?php endif; ?>

    <!-- Content -->
    <article class="prose prose-lg max-w-none" data-reveal="rise">
      <?php the_content(); ?>
    </article>
</div>

<?php
get_footer();
