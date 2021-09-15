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
/**
 * Event Details
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



if ( get_option( 'sportspress_event_show_details', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;

$data = array();

if ( 'yes' === get_option( 'sportspress_event_show_date', 'yes' ) ) {
	$date = get_the_time( get_option('date_format'), $id );
	$data[ __( 'Date', 'sportspress' ) ] = $date;
}

if ( 'yes' === get_option( 'sportspress_event_show_time', 'yes' ) ) {
	$time = get_the_time( get_option('time_format'), $id );
	$data[ __( 'Time', 'sportspress' ) ] = apply_filters( 'sportspress_event_time', $time, $id );
}

$taxonomies = apply_filters( 'sportspress_event_taxonomies', array( 'sp_league' => null, 'sp_season' => null ) );

foreach ( $taxonomies as $taxonomy => $post_type ):
	$terms = get_the_terms( $id, $taxonomy );
	if ( $terms ):
		$obj = get_taxonomy( $taxonomy );
		$term = array_shift( $terms );
		$data[ $obj->labels->singular_name ] = $term->name;
	endif;
endforeach;

if ( 'yes' === get_option( 'sportspress_event_show_day', 'yes' ) ) {
	$day = get_post_meta( $id, 'sp_day', true );
	if ( '' !== $day ) {
		$data[ __( 'Match Day', 'sportspress' ) ] = $day;
	}
}

if ( 'yes' === get_option( 'sportspress_event_show_full_time', 'yes' ) ) {
	$full_time = get_post_meta( $id, 'sp_minutes', true );
	if ( '' === $full_time ) {
		$full_time = get_option( 'sportspress_event_minutes', 90 );
	}
	$data[ __( 'Full Time', 'sportspress' ) ] = $full_time . '\'';
}

$data = apply_filters( 'sportspress_event_details', $data, $id );

if ( ! sizeof( $data ) ) return;
?>


<div class="sp-template sp-template-event-details">
	<h4 class="sp-table-caption"><?php _e( 'Details', 'sportspress' ); ?></h4>
	<div class="sp-table-wrapper">
		<table class="sp-event-details sp-data-table<?php if ( $scrollable ) { ?> sp-scrollable-table<?php } ?>">
			<thead>
				<tr>
					<?php $i = 0; foreach( $data as $label => $value ):	?>
						<th><?php echo $label; ?></th>
					<?php $i++; endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<tr class="odd">
					<?php $i = 0; foreach( $data as $value ):	?>
						<td><?php echo $value; ?></td>
					<?php $i++; endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div id="ticket_section">
    <?php 
$post_id = get_the_id();
Tribe__Tickets__Tickets_View::instance()->get_tickets_block( $post_id );
?>
</div>

</div>

<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>

