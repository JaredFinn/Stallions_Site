<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
/*
Template Name: CURRENT SPONSOR 2
Template Post Type: page
*/
// Page code here..

 remove_filter ('the_content', 'wpautop'); ?> <?php // removes wp auto adding p and br ?>
<?php get_header(); ?>
<?php /* The Loop */ ?>
<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>    
    <!-- do stuff ... -->
    
<?php endwhile; ?>
<?php endif; ?>

<?php /* The Loop END */ ?>
<div class="pagew">
    <section class="collection-current-sponsors">

    <div class="section-titlestyles_Container">
    <div class="section-titlestyles_TitleContainer">
        <h2 class="section-titlestyles_Title-sc">CURRENT PARTNERS</h2>
    </div>
    <div class="section-titlestyles_BottomLine-sc"></div>
    
    <?php the_content(); ?>
    <?php
 
        $args = array(
            'post_type'  => 'sponsor',
            'posts_per_page' => -1, //for all posts
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
            'suppress_filters' => true, 
            'meta_key' => '_thumbnail_id',
            'meta_query' => array(
                array(
                    'key' => 'link'
                )
            ),
            
        );
        // The Query
        $the_query = new WP_Query($args);?>
  

        <div class="sponsor-collectionstyles">

       
        <?php 
         // The Loop
        if ( $the_query->have_posts() ) {?>
          
            <?php while ( $the_query->have_posts() ) { ?>
                <div class="sponsors-collectionstyles_ContainerSponsorCard">
                <?php 
                $the_query->the_post();
                $name=  get_the_title();
                $description= get_the_content();
                $linkvalue=get_post_meta(get_the_ID(), 'link', true);
                if ( $linkvalue ) {
                    // Returns an empty string for invalid URLs
                    $link = esc_url( $linkvalue );
                }

                $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                ?>

                <a href=<?php echo $link;?> class="sponsor-cardstyles_Container">
                <div class="sponsor-cardstyles_SponsorImage">
                <img src=<?php echo $url;?> style="sponsor-background-image">
                </div>
                    <div class="sponsor-cardstyles_SponsorInfoContainer">
                        <div class="sponsor-cardstyles_SponsorInfo">
                            <div color="#333333" class="sponsor-cardstyles_SponsorName"><?php echo $name;?></div>
                            <div color="#999999" class="sponsor-cardstyles_SponsorDescription"><?php echo $description;?></div>
                        </div>
                    </div>
                </a>
            </div>

    <?php    
        }
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();
    ?>

        </div>
    </div>
  </section>
</div>

<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>
<?php
