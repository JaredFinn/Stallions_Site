<?php remove_filter ('the_content', 'wpautop'); ?> <?php // removes wp auto adding p and br ?>
<?php get_header(); ?>
<?php /* The Loop */ ?>
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>    
	<!-- do stuff ... -->
	<?php endwhile; ?>
<?php endif; ?>
<?php /* The Loop END */ ?>


<?php the_content(); ?>
<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>