<?php /*
get_header();
// Start the Loop 
echo "This is a news article";
while (have_posts()) : the_post();
   get_template_part('template-parts/post/content', get_post_format());
endwhile; // End of the loop.
get_footer();
*/
?>

<?php 
    get_header();
?>
<!--<meta content="width=device-width, initial-scale=1" name="viewport">-->
<?php /* The Loop */ ?>
<?php if ( have_posts() ) : ?>
    <div class='pagew'>
	<?php while ( have_posts() ) : the_post(); 
	    //get_template_part( 'content', get_post_format() ); 
	    
	    echo "<h3>";
	    the_title();
	    echo "</h3>";
	    the_post_thumbnail( 'large','style=max-width:100%;height:auto;display:block;margin-left:auto;margin-right:auto;');
	    //the_post_thumbnail();
	    //echo "image: ".get_the_post_thumbnail_url();
	?>
	<div style="padding-left:5%;padding-right:5%;">
	    <?php the_content(); ?>
	</div>
	    
	
	<!-- do stuff ... -->
	<?php //echo "This is a news post."; ?>
	<?php endwhile; ?>
	</div>
<?php endif; ?>
<?php /* The Loop END */ ?>


<?php 
    //echo "<h2>".get_the_title()."</h2>";
    //the_content();
?>

<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>