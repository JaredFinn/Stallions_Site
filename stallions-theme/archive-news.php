<?php
/**
 * The template for displaying archive pages
 */

get_header();

$description = get_the_archive_description();
?>

<?php if ( have_posts() ) : ?>
    <div class='pagew'>
	<header class="page-header alignwide" style="padding-left:5%;padding-right:5%;">
		<?php //the_archive_title( '<h2 class="page-title">', '</h2>' ); ?>
		<h2 class="page-title" style="padding-top: 15px;padding-bottom: 15px;font-size: 20px;"><b>News</b></h2><?php get_search_form(); ?> 
		
		
        
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
	</header><!-- .page-header -->
    <!-- <ol class='news-articles'> -->
	<?php while ( have_posts() ) : ?>
		<section class='news_article_section' style='min-height:200px;padding-left:5%;padding-right:5%'>
		    <?php the_post(); ?>
            	<!--<li class='news-article'>-->
            		<article id="post-<?php the_ID(); ?>" <?php post_class();?> style="margin-bottom: 20px;padding-top: 21px;border-top-width:1px;border-top-style: solid;">
                
            	<header class="entry-header">
            		
            		
            		<?php 
            		    echo '<a href="'.esc_url( get_permalink()).'" id="articlelink" style="text-decoration:none;">';
            		    $imgattr=array(
                                "style" => "float:right;",
                        );
                        the_post_thumbnail('thumbnail', $imgattr); 
                        if ( is_singular() ) : ?>
            			<?php the_title( '<h2 class="entry-title default-max-width" style="font-size: 2em">', '</h2>' ); ?>
            		<?php else : ?>
            			<?php the_title('<h2 class="entry-title default-max-width" style="font-size:2em;text-decoration:underline;">', '</h2>' ); ?>
            		<?php endif; ?>
                    <?php 
                        
                    ?>
            		<?php //twenty_twenty_one_post_thumbnail(); ?>
            	</header><!-- .entry-header -->
                </a>
            	<div class="entry-content">
            		<?php
            		//the_content(
            			//twenty_twenty_one_continue_reading_text()
            		//);
                    the_excerpt();?>
                    <span style='font-style: italic;'>
                        <?php echo get_the_date(/*'F j, Y'*/); ?> <?php the_time(); ?>
                    </span>
                    
            		<?php /*wp_link_pages(
            			array(
            				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
            				'after'    => '</nav>',
            				// translators: %: page number. 
            				'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
            			)
            		);*/ ?>
            

            	</div><!-- .entry-content -->
                
            	<footer class="entry-footer default-max-width">
            		<?php //twenty_twenty_one_entry_meta_footer(); ?>
            	</footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->
            <!--</li>-->
            </section>
	<?php endwhile; ?>
	<!-- </ol> -->
    	<div id='archivelinks'>
        	<?php 
        	   
            ?>
            
            <p>
                <h2 style='text-align: center;'>Yearly Archives</h2>
                <?php
            	   $args = array(
            	       'type' => 'yearly',
            	       'echo' => '0',
            	       'post_type' => 'news',
            	   );
                    $year_archives = wp_get_archives($args); 
                    echo '<ul style="text-align: center">'.$year_archives.'</ul>';
                ?>
            </p>
            <p>
                <h2 style="text-align: center">Monthly Archives</h2>
                <?php
            	   $args = array(
            	       'type' => 'monthly',
            	       'echo' => '0',
            	       'post_type' => 'news',
            	   );
            	   $month_archives = wp_get_archives($args); 
            	   echo '<ul style="text-align: center">'.$month_archives.'</ul>';
            	?>
        	</p>
    	</div>
    </div>

<?php endif; ?>

<?php get_footer(); ?>
