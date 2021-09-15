
<?php /*
get_header();
// Start the Loop
echo "This is a player article";
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
    <?php while ( have_posts() ) : the_post();
    	    //get_template_part( 'content', get_post_format() );


    	    $post_id = get_the_ID();
                $name=get_post_meta($post_id, '_coach_name', true);
                $position=get_post_meta($post_id, '_coach_position', true);
                $url= wp_get_attachment_url(get_post_thumbnail_id($post_id));
    	?>

      <div class="pagew" style="min-height: 300px;">
      <div class="biow">
      <div class="bio">

      <img class="bioimg" src=<?php echo $url?> />
      <h2><?php echo $name;?></h2>
      <h4><i><?php echo $position;?></i></h4>
      <br>
      <?php the_content(); ?>
      </div>
      <div></div>
      </div>
      </div>
    	<!-- do stuff ... -->
    	<?php //echo "This is a news post."; ?>
    	<?php endwhile; ?>

<?php endif; ?>
<?php /* The Loop END */ ?>


<?php
    //echo "<h2>".get_the_title()."</h2>";
    //the_content();
?>

<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>
