<?php
get_header();

$client = carbon_get_the_post_meta('project_client');
$role = carbon_get_the_post_meta('project_role');
$year = carbon_get_the_post_meta('project_year');
$link = carbon_get_the_post_meta('project_link');
$video = carbon_get_the_post_meta('project_video');
$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>

<div class="relative w-full pt-32 pb-24 bg-ag-black">
    <div class="max-w-[1000px] mx-auto px-6 lg:px-10">
        <!-- Back -->
        <a href="<?php echo get_post_type_archive_link('project'); ?>"
          class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-white transition-colors mb-12">
          <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true"><path d="M13 5H1M6 1L1 5L6 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <?php echo theme_t('Назад до проектів', 'Back to projects', 'Назад к проектам'); ?>
        </a>

        <!-- Headline -->
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white tracking-tighter mb-8 leading-[1.1]" data-reveal="rise">
          <?php the_title(); ?>
        </h1>
        
        <!-- Summary -->
        <?php if ( has_excerpt() ) : ?>
        <p class="text-white/70 text-lg md:text-xl leading-relaxed mb-10 max-w-2xl" data-reveal="rise" data-delay="1">
          <?php echo get_the_excerpt(); ?>
        </p>
        <?php endif; ?>

        <!-- Meta row -->
        <div class="flex flex-wrap gap-x-12 gap-y-6 border-t border-white/10 pt-8" data-reveal="rise" data-delay="2">
          <?php if ( $client ) : ?>
          <div>
            <span class="text-xs font-bold uppercase tracking-widest text-gray-500 block mb-1"><?php echo theme_t('Клієнт', 'Client', 'Клиент'); ?></span>
            <span class="text-sm font-semibold text-white"><?php echo esc_html($client); ?></span>
          </div>
          <?php endif; ?>
          
          <?php if ( $role ) : ?>
          <div>
            <span class="text-xs font-bold uppercase tracking-widest text-gray-500 block mb-1"><?php echo theme_t('Роль', 'Role', 'Роль'); ?></span>
            <span class="text-sm font-semibold text-white"><?php echo esc_html($role); ?></span>
          </div>
          <?php endif; ?>
          
          <?php if ( $year ) : ?>
          <div>
            <span class="text-xs font-bold uppercase tracking-widest text-gray-500 block mb-1"><?php echo theme_t('Рік', 'Year', 'Год'); ?></span>
            <span class="text-sm font-semibold text-white"><?php echo esc_html($year); ?></span>
          </div>
          <?php endif; ?>
          
          <?php if ( $link ) : ?>
            <div>
              <span class="text-xs font-bold uppercase tracking-widest text-gray-500 block mb-1">URL</span>
              <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-1 text-sm font-semibold text-ag-accent hover:text-white transition-colors">
                <?php echo theme_t('Відвідати проект', 'Visit project', 'Посетить проект'); ?>
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" aria-hidden="true"><path d="M2 8L8 2M8 2H4M8 2V6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </a>
            </div>
          <?php endif; ?>
        </div>
    </div>
</div>

<div class="bg-ag-white text-ag-black py-24 rounded-t-[3rem] -mt-12 relative z-20 shadow-[0_-20px_40px_rgba(0,0,0,0.3)]">
    <div class="max-w-[1000px] mx-auto px-6 lg:px-10">
        <!-- Cover media -->
        <?php if ( $video || $thumbnail ) : ?>
          <div class="overflow-hidden mb-16 bg-gray-100 rounded-[2rem] shadow-2xl aspect-video" data-reveal="rise">
            <?php if ( $video ) : ?>
              <iframe
                src="<?php echo esc_url($video); ?>"
                class="w-full h-full pointer-events-none"
                allow="autoplay; encrypted-media"
                allowfullscreen
              ></iframe>
            <?php else : ?>
              <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover" />
            <?php endif; ?>
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
