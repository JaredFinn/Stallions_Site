<?php
/*
Template Name: Season Tickets
Template Post Type: page
*/
// Page code here..
?>
<?php remove_filter ('the_content', 'wpautop'); ?> <?php // removes wp auto adding p and br ?>
<?php get_header(); ?>
<?php /* The Loop */ ?>
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>    
	<!-- do stuff ... -->
	
	<?php endwhile; ?>
<?php endif; ?>
<?php /* The Loop END */ ?>
<div class="pagew">


<?php the_content(); ?>


<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


?>


<?php 
$post_id = get_the_id();
Tribe__Tickets__Tickets_View::instance()->get_tickets_block( $post_id );
?>
</div>

<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>

