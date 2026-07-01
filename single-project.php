<?php
get_header();

$client = carbon_get_the_post_meta('project_client');
$role = carbon_get_the_post_meta('project_role');
$year = carbon_get_the_post_meta('project_year');
$link = carbon_get_the_post_meta('project_link');
$video = carbon_get_the_post_meta('project_video');
$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>

<section class="breakout relative min-h-[50vh] md:min-h-[60vh] flex flex-col items-center justify-center pt-32 md:pt-48 pb-12 md:pb-20 text-center overflow-hidden mb-8 md:mb-12">
    <!-- Particles Container -->
    <div id="hero-particles" class="absolute inset-0 z-0 pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <!-- Back -->
        <a href="<?php echo get_post_type_archive_link('project'); ?>"
          class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-ag-black transition-colors mb-12" data-reveal="rise">
          <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true"><path d="M13 5H1M6 1L1 5L6 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <?php echo theme_t('Назад до проєктів', 'Back to projects', 'Назад к проектам'); ?>
        </a>

        <!-- Headline -->
        <h1 class="text-4xl md:text-6xl lg:text-[72px] font-[450] text-ag-black leading-[1.1] md:leading-[1.2] mb-6 tracking-tight" data-reveal="letters">
          <?php the_title(); ?>
        </h1>
        
        <!-- Summary -->
        <?php if ( has_excerpt() ) : ?>
        <p class="text-[16px] font-normal text-gray-500 leading-relaxed max-w-2xl mx-auto mb-10" data-reveal="rise">
          <?php echo get_the_excerpt(); ?>
        </p>
        <?php endif; ?>

        <!-- Meta row -->
        <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-6 pt-4" data-reveal="rise" data-delay="2">
          <?php if ( $client ) : ?>
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 block mb-1"><?php echo theme_t('Клієнт', 'Client', 'Клиент'); ?></span>
            <span class="text-sm font-semibold text-ag-black"><?php echo esc_html($client); ?></span>
          </div>
          <?php endif; ?>
          
          <?php if ( $role ) : ?>
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 block mb-1"><?php echo theme_t('Роль', 'Role', 'Роль'); ?></span>
            <span class="text-sm font-semibold text-ag-black"><?php echo esc_html($role); ?></span>
          </div>
          <?php endif; ?>
          
          <?php if ( $year ) : ?>
          <div class="text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 block mb-1"><?php echo theme_t('Рік', 'Year', 'Год'); ?></span>
            <span class="text-sm font-semibold text-ag-black"><?php echo esc_html($year); ?></span>
          </div>
          <?php endif; ?>
          
          <?php if ( $link ) : ?>
            <div class="text-center">
              <span class="text-xs font-bold uppercase tracking-widest text-gray-400 block mb-1">URL</span>
              <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-1 text-sm font-semibold text-ag-accent hover:text-ag-black transition-colors">
                <?php echo theme_t('Відвідати проєкт', 'Visit project', 'Посетить проект'); ?>
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" aria-hidden="true"><path d="M2 8L8 2M8 2H4M8 2V6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </a>
            </div>
          <?php endif; ?>
        </div>
    </div>
</section>

<div class="py-12 md:py-24 relative z-20">
    <div class="max-w-[1000px] mx-auto px-6 lg:px-10">
        <!-- Cover media -->
        <?php if ( $video || $thumbnail ) : ?>
          <div class="overflow-hidden mb-16 bg-ag-light rounded-[32px] aspect-video w-full border border-gray-100" data-reveal="rise">
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

        <!-- Content -->
        <article class="prose prose-lg md:prose-xl max-w-none prose-headings:font-[450] prose-strong:font-[450] prose-a:text-ag-accent prose-a:no-underline hover:prose-a:underline" data-reveal="rise">
          <?php the_content(); ?>
        </article>
    </div>
</div>

<?php
get_footer();
