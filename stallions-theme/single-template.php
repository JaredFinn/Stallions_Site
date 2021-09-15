<?php
get_header();
if (have_posts()) :
 while (have_posts()) :
 the_post();
 the_content();
 endwhile;
endif;?>
		<?php // main content // ?>
        <div class="pagew">
            Cantine Page
        </div>
<?php
get_footer();
?>