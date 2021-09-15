<?php
/*
Template Name: CURRENT SPONSOR
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
<meta content="width=device-width, initial-scale=1" name="viewport" />
<?php /* The Loop END */ ?>
<div class="pagew">
    
    <?php the_content(); ?>


<?php


	$args = array(
	    'post_type'=> 'sponsor',
	    'order'    => 'ASC'
	);              

	$the_query = new WP_Query( $args );
	if($the_query->have_posts() ) : 
	    while ( $the_query->have_posts() ) : 
	    		$featured_img = wp_get_attachment_image_src( $post->ID );
        print the_title();

	    endwhile; 
	    wp_reset_postdata(); 
	else: 
	endif;


	  $args = array(
	    'post_type'     => 'sponsor',
	    'post_status'   => 'publish',
	    'fields'        => 'ids',
	    'meta_query'    => array(
	      array(
	        'key'        => 'link',
	      ),
	      array(
	        'key'        => 'description',
	      ),
	    ),
	  );

	  // The Query
	  $result_query = new WP_Query( $args );

	  $ID_array = $result_query->posts;

	  // Restore original Post Data
	  wp_reset_postdata();

	  return $ID_array;

	
?>
</div>




<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>



global $wpdb;
$myposts = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_title LIKE '%s'", '%'. $wpdb->esc_like( $title ) .'%') );