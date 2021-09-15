<?php
/*
Template Name: Advisory_Board
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
    <div class="boardw">
    <p style="text-align: center; font-size: 20px;">
      The Advisory Committee is made up of key figures in the Saugerties community who will help the franchise create business relationships with various organizations, departments, and individuals that play a key role in our successful community.
    </p>
    <div class="profilesw">
      <ul class="profiles">
        <?php
        $args = array(
           'post_type'  => 'advisory_board',
           'posts_per_page' => -1, //for all posts
           'post_status' => 'publish',
           'orderby' => '_ab_order',
           'order' => 'ASC',
       );

       $the_query = new WP_Query($args);

        if ( $the_query->have_posts() ) { ?>

            <?php
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $name=strtoupper(get_post_meta(get_the_ID(), '_ab_name', true));
                $position=get_post_meta(get_the_ID(), '_ab_position', true);
                $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                $link=get_permalink();

            ?>
            <li class="profiles"><a href="<?php echo $link; ?>">
            <img class="profileimg" src="<?php echo $url;?>" /></a>
            <div class="profilename"><?php echo $name;?></div>
            <div class="profilerole"><?php echo $position;?></div>
            &nbsp;</li>
            <?php
            }
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_query();
        wp_reset_postdata();
        ?>
      </ul>
    </div>
  </div>
</div>



<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>
<?php
