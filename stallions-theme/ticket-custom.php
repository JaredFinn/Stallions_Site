<?php
/*
Template Name: ticket
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



<?php the_content(); ?>
<div class="pagew">
<?php
/**
 * Event Logos
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.6.10
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_event_show_logos', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$teams = (array) get_post_meta( $id, 'sp_team' );
$teams = array_filter( $teams, 'sp_filter_positive' );
$reverse_teams = get_option( 'sportspress_event_reverse_teams', 'no' ) === 'yes' ? true : false;
if ( $reverse_teams ) {
	$teams = array_reverse( $teams );
}

if ( ! $teams ) return;

$layout = get_option( 'sportspress_event_logos_format', 'inline' );

$show_team_names = get_option( 'sportspress_event_logos_show_team_names', 'yes' ) === 'yes' ? true : false;
$show_time = get_option( 'sportspress_event_logos_show_time', 'no' ) === 'yes' ? true : false;
$show_results = get_option( 'sportspress_event_logos_show_results', 'no' ) === 'yes' ? true : false;
$link_teams = get_option( 'sportspress_link_teams', 'no' ) === 'yes' ? true : false;

if ( $show_results ) {
	$results = sp_get_main_results( $id );
	if ( empty( $results ) ) {
		$show_results = false;
	} else {
		$show_time = false;
		if ( $reverse_teams ) {
			$results = array_reverse( $results );
		}
	}
} else {
	$results = array();
}

sp_get_template( 'event-logos-' . $layout . '.php', array(
	'id' => $id,
	'teams' => $teams,
	'results' => $results,
	'show_team_names' => $show_team_names,
	'show_time' => $show_time,
	'show_results' => $show_results,
	'link_teams' => $link_teams,
) );

do_action( 'sportspress_after_event_logos', $id );

?>


<?php 
$post_id = get_the_id();
Tribe__Tickets__Tickets_View::instance()->get_tickets_block( $post_id );
?>
</div>

<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>

