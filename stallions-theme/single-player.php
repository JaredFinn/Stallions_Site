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
                $player_firstname=get_post_meta($post_id, '_player_firstname', true);
                $player_lastname=get_post_meta($post_id, '_player_lastname', true);
                $player_name=$player_firstname.' '.$player_lastname;
                $player_number=get_post_meta($post_id, '_player_number', true);
                if(get_post_meta($post_id, '_player_bats', true)=="left"){
                    $player_bats="L";
                }
                if(get_post_meta($post_id, '_player_bats', true)=="right"){
                    $player_bats="R";
                }
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="switch"){
                    $player_bats="S";
                }
                if(get_post_meta($post_id, '_player_throws', true)=="left"){
                    $player_throws="L";
                }
                if(get_post_meta($post_id, '_player_throws', true)=="right"){
                    $player_throws="R";
                }
                $player_height=get_post_meta($post_id, '_player_height', true);
                $player_weight=get_post_meta($post_id, '_player_weight', true);
                $player_weight.=' lbs';
                $player_resides=get_post_meta($post_id, '_player_resides', true);
                $player_college=get_post_meta($post_id, '_player_college', true);
                $player_position=get_post_meta($post_id, '_player_position', true);
                $url= wp_get_attachment_url(get_post_thumbnail_id($post_id));
    	?>

    	    
    	<div class="pagew">
        <div class="playerbiow">
            <img src=<?php echo $url; ?> class="bioimg">
            <h2>#<?php echo $player_number; ?> 
                <?php echo '    '; 
                    echo $player_name;?>
            </h2>
            <h4><b>Position:</b> <?php echo $player_position; ?></h4>
        <!--<h4><b>Date of Birth:</b> MM/DD/YYYY</h4>-->
            <h4><b>Height:</b> <?php echo $player_height; ?></h4>
            <h4><b>Weight:</b> <?php echo $player_weight; ?></h4>
            <h4><b>College:</b> <?php echo $player_college; ?></h4>
            <h4><b>Bats:</b> <?php echo $player_bats; ?></h4>
            <h4><b>Throws:</b> <?php echo $player_throws; ?></h4>
            <h4><b>Resides:</b> <?php echo $player_resides; ?></h4>
            <h3>Biography:</h3>
                <?php the_content(); ?>
            <div>
            </div>
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