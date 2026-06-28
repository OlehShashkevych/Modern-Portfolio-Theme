<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php 
        // We do not output any extra containers if this is a Gutenberg page
        // The blocks themselves have the max-w-5xl containers where needed (e.g. About Section)
        the_content(); 
    ?>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
