
<?php
/*
Template Name: calendar
Template Post Type: page
*/
?>




        <link rel="stylesheet" type="text/css" href="\wp-content\themes\stallions-theme\fontawesome\css/all.css" media="all">
        <link rel="stylesheet" href="/wp-content/themes/stallions-theme/fontawesome/css/fontawesome.min.css">
<?php remove_filter ('the_content', 'wpautop'); ?> <?php // removes wp auto adding p and br ?>
<?php get_header(); ?>
<?php /* The Loop */ ?>
<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>    
    <!-- do stuff ... -->
    
    <?php endwhile; ?>
<?php endif; ?>
<meta content="width=device-width, initial-scale=1" name="viewport" />
<?php /* The Loop END */ ?>
<div class="pagew">
    
    <?php the_content(); ?>
<!--application/x-httpd-php event-calendar.php ( PHP script text )-->


<?php
get_footer();
?>