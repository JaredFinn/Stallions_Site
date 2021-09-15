<?php
get_header();
if (have_posts()) :
 while (have_posts()) :
 //the_post();
 //the_content();
 endwhile;
endif;?>
<meta content="width=device-width, initial-scale=1" name="viewport" />
		<?php // main content // ?>
        <div class="mainw">
            <div class="contentw">
                <div class="carouselw">
                    <?php //echo do_shortcode('[slide-anything id="410"]'); ?>
                    
                    <div class="white" style="background:rgb(255, 255, 255); border:solid 0px #f0f0f0; border-radius:0px; padding:0px 0px 0px 0px;">
                    <div id="sample_slider" class="owl-carousel owl-pagination-true autohide-arrows owl-loaded" data-slider-id="sample_slider" style="visibility: visible;">
                    <div class="owl-stage-outer"><div class="owl-stage" style="transform: translate3d(-3301px, 0px, 0px); transition: all 0.3s ease 0s; width: 5283px;">
                    
                    <!-- Start of carousel -->
					<?php $loop = new WP_Query(
							array(
								'post_type' => 'news', 
								'meta_query' => array(
								   'relation' => 'AND',
									array(
										'key'     => 'show_in_carousel',
										'value'   => '1',
										'compare' => '=',
									),
									array(
										'key'     => 'show_in_carousel',
										'compare' => 'EXISTS',
									)
							   ),
								'posts_per_page' => 4, 
								'orderby'=> 'ASC'
							)
						);
					?>
					    <?php 
                            $x = 0; 
                            $taglines=array();
				        ?>
                          <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                <?php 
                                    $x++;
                                    $taglines[$x-1]=get_post_meta(get_the_ID(), 'carousel_caption', true );;
                                    $title=get_the_title();
                                    $excerpt=get_the_excerpt();
                                    $image = array("");
                                    $link = get_permalink($post->ID);
                                    if (has_post_thumbnail( $post->ID ) ){
                                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
                                    } 
                                    $img="&quot;".$image[0]."&quot;";
                                    echo 
            						"
            							<a href='$link' style='text-decoration: none';>
            							<div class='owl-item' style='width: 660.325px;'>
            							
            							<div id='sample_slider_slide0$x' style='padding:0% 0%; margin:0px 0%; background-image:linear-gradient(to bottom, rgba(0,0,0,0) 0%,rgba(0,0,0,0) 66%,rgba(0,0,0,0.52) 76%,rgba(0,0,0,0.67) 81%,rgba(0,0,0,0.8) 85%,rgba(0,0,0,0.9) 91%,rgba(0,0,0,0.92) 95%,rgba(0,0,0,0.92) 100%),url($img); background-position:center top; background-size:cover; background-repeat:no-repeat; background-color:rgba(255, 255, 255, 0); min-height:360px; '><div style='text-align: center; padding-bottom: 10px; /*background-color: rgba(0,0,0,0.7); */position:absolute; bottom: 0; width:100%'>
            							<h3 class='carouselh3'>$title</h3>
            							<p class='carouselp'>$excerpt</p>
            							</a></div></div></div>
						            ";
						            /*echo 
            						"
            							<a href='$link' style='text-decoration: none';>
            							<div class='owl-item' data-dot='<p>Test text</p>' style='width: 660.325px;'>
            							<div id='sample_slider_slide0$x' class='sa_hover_container' style='padding:0% 0%; margin:0px 0%; background-image:url($img); background-position:center top; background-size:cover; background-repeat:no-repeat; background-color:rgba(255, 255, 255, 0); min-height:360px; '><div style='text-align: center; padding-bottom: 10px; background-color: rgba(0,0,0,0.7); position:absolute; bottom: 0;'>
            							<h3 class='carouselh3'>$title</h3>
            							<p class='carouselp'>$excerpt</p>
            							</a></div></div></div>
						            ";*/
                                ?>
                          <?php endwhile; ?>
                    <!-- End of carousel -->
                    
                    </div></div>
                    
                    <div class="owl-nav">
                        <button type="button" role="presentation" class="owl-prev">Previous</button>
                        <button type="button" role="presentation" class="owl-next">Next</button>
                    </div>
                    <!--<div class="owl-dots"><button role="button" class="owl-dot"><span>?Button1?</span></button><button role="button" class="owl-dot"><span>?Text2?</span></button><button role="button" class="owl-dot"><span>?Text3?</span></button><button role="button" class="owl-dot"><span>?Text4?</span></button></div>-->
                    </div>
                    </div>
                    
                    <script type="text/javascript">
                    	jQuery(document).ready(function() {
                    		jQuery('#sample_slider').owlCarousel({
                    			items : 1,
                    			smartSpeed : 300,
                    			autoplay : true,
                    			autoplayTimeout : 4000,
                    			autoplayHoverPause : true,
                    			smartSpeed : 300,
                    			fluidSpeed : 300,
                    			autoplaySpeed : 300,
                    			navSpeed : 300,
                    			dotsSpeed : 300,
                    			loop : true,
                    			startPosition : 0,
                    			nav : true,
                    			navText : ['Previous','Next'],
                    			dots : true,
                    			//Enables text on carosuel
                    			dotsData: false,
                    			responsiveRefreshRate : 200,
                    			slideBy : 1,
                    			mergeFit : true,
                    			autoHeight : false,
                    			mouseDrag : true,
                    			touchDrag : true
                    		});
                    		jQuery('#sample_slider').css('visibility', 'visible');
                    		var owl_goto = jQuery('#sample_slider');
                    		jQuery('.sample_slider_goto1').click(function(event){
                    			owl_goto.trigger('to.owl.carousel', 0);
                    		});
                    		jQuery('.sample_slider_goto2').click(function(event){
                    			owl_goto.trigger('to.owl.carousel', 1);
                    		});
                    		jQuery('.sample_slider_goto3').click(function(event){
                    			owl_goto.trigger('to.owl.carousel', 2);
                    		});
                    		jQuery('.sample_slider_goto4').click(function(event){
                    			owl_goto.trigger('to.owl.carousel', 3);
                    		});
                    		var resize_410 = jQuery('.owl-carousel');
                    		resize_410.on('initialized.owl.carousel', function(e) {
                    			if (typeof(Event) === 'function') {
                    				window.dispatchEvent(new Event('resize'));
                    			} else {
                    				var evt = window.document.createEvent('UIEvents');
                    				evt.initUIEvent('resize', true, false, window, 0);
                    				window.dispatchEvent(evt);
                    			}
                    		});
                    		jQuery('.owl-dot').css("overflow", "hidden");
                    		jQuery('.owl-dot').css("text-overflow", "ellipsis");
                    		jQuery('.owl-dot').css("white-space", "nowrap");
                    		jQuery('.owl-dot:nth-of-type(1)').html("<span><?php echo $taglines[0]; ?></span>");
                    		jQuery('.owl-dot:nth-of-type(2)').html("<span><?php echo $taglines[1]; ?></span>");
                    		jQuery('.owl-dot:nth-of-type(3)').html("<span><?php echo $taglines[2]; ?></span>");
                    		jQuery('.owl-dot:nth-of-type(4)').html("<span><?php echo $taglines[3]; ?></span>");
                    		owl_goto.trigger('to.owl.carousel', 0);
                    	});
                    </script>

                </div> 
                
        <div class="uppercontentw">
                <?php if (false): ?>
					<div class="countdownwid">
						 <?php if ( is_active_sidebar( 'countdown-widget' ) ) : ?>
							<div id="main-slider-widget-area" class="widget-area" role="complementary">
							  <?php dynamic_sidebar( 'countdown-widget' ); ?>
							</div><!-- .widget-area -->
						<?php endif; ?>
					</div>
					
					<div class="eventlistw">
						 <?php if ( is_active_sidebar( 'upcoming-widget' ) ) : ?>
							<div id="main-slider-widget-area" class="widget-area" role="complementary">
							  <?php dynamic_sidebar( 'upcoming-widget' ); ?>
							</div><!-- .widget-area -->
						<?php endif; ?>
					</div>
				<?php else: ?>
					<div class="fronttwitterfeed">
						<div class="nextgame" style="height:100px">
							<?php 
							
							
							
							
							
							
							
							
							
$defaults = array(
	'id' => null,
	'event' => null,
	'title' => false,
	'status' => 'future',
	'format' => 'all',
	'date' => 'default',
	'date_from' => 'default',
	'date_to' => 'default',
	'date_past' => 'default',
	'date_future' => 'default',
	'date_relative' => 'default',
	'day' => 'default',
	'league' => null,
	'season' => null,
	'venue' => null,
	'team' => null,
	'teams_past' => null,
	'date_before' => null,
	'player' => null,
	'number' => 1,
	'show_team_logo' => true,
	'link_teams' => false,
	'link_events' => true,
	'paginated' => false,
	'rows' => 1,
	'orderby' => 'post_date',
	'order' => 'DESC',
	'columns' => null,
	'show_all_events_link' => false,
	'show_title' => true,
	'show_league' => false,
	'show_season' => false,
	'show_matchday' => false,
	'show_venue' => false,
	'hide_if_empty' => false,
);
extract( $defaults, EXTR_SKIP );

$calendar = new SP_Calendar( $id );

if ( $status != 'default' )
	$calendar->status = $status;
if ( $format != 'default' )
	$calendar->event_format = $format;
if ( $date != 'default' )
	$calendar->date = $date;
if ( $date_from != 'default' )
	$calendar->from = $date_from;
if ( $date_to != 'default' )
	$calendar->to = $date_to;
if ( $date_past != 'default' )
	$calendar->past = $date_past;
if ( $date_future != 'default' )
	$calendar->future = $date_future;
if ( $date_relative != 'default' )
	$calendar->relative = $date_relative;
if ( $event ) 
	$calendar->event = $event;
if ( $league )
	$calendar->league = $league;
if ( $season )
	$calendar->season = $season;
if ( $venue )
	$calendar->venue = $venue;
if ( $team )
	$calendar->team = $team;
if ( $teams_past )
	$calendar->teams_past = $teams_past;
if ( $date_before )
	$calendar->date_before = $date_before;
if ( $player )
	$calendar->player = $player;

	
if ( $day != 'default' )
	$calendar->day = $day;

//if ( $order != 'default' )
$calendar->order = "ASC";
//if ( $orderby != 'default' )
$calendar->orderby = "post_date";

$data = $calendar->data();
$usecolumns = $calendar->columns;

if ( isset( $columns ) ):
	if ( is_array( $columns ) )
		$usecolumns = $columns;
	else
		$usecolumns = explode( ',', $columns );
endif;

if ( $hide_if_empty && empty( $data ) ) return false;

if ( $show_title && false === $title && $id ):
	$caption = $calendar->caption;
	if ( $caption )
		$title = $caption;
	else
		$title = get_the_title( $id );
endif;

?>
<div class="sp-template sp-template-event-blocks" style="margin-bottom:0px !important; padding: 1px; max-height">
	<div class="sp-table-wrapper">
		<table class="sp-event-blocks sp-data-table<?php if ( $paginated ) { ?> sp-paginated-table<?php } ?>" data-sp-rows="<?php echo $rows; ?>">
			<thead><tr><th></th></tr></thead> <?php # Required for DataTables ?>
			<tbody>
				<?php
				$i = 0;

				if ( intval( $number ) > 0 )
					$limit = $number;

				foreach ( $data as $event ):
					if ( isset( $limit ) && $i >= $limit ) continue;

					$permalink = get_post_permalink( $event, false, true );
					$results = sp_get_main_results_or_time( $event );

					$teams = array_unique( get_post_meta( $event->ID, 'sp_team' ) );
					$teams = array_filter( $teams, 'sp_filter_positive' );
					$logos = array();
					$event_status = get_post_meta( $event->ID, 'sp_status', true );

					if ( get_option( 'sportspress_event_reverse_teams', 'no' ) === 'yes' ) {
						$teams = array_reverse( $teams , true );
						$results = array_reverse( $results , true );
					}

					if ( $show_team_logo ):
						$j = 0;
						foreach( $teams as $team ):
							$j++;
							$team_name = get_the_title( $team );
							if ( has_post_thumbnail ( $team ) ):
								$logo = get_the_post_thumbnail( $team, 'sportspress-fit-icon', array( 'itemprop' => 'logo' ) );

								if ( $link_teams ):
									$team_permalink = get_permalink( $team, false, true );
									$logo = '<a href="' . $team_permalink . '" itemprop="url" content="' . $team_permalink . '">' . $logo . '</a>';
								endif;

								$logo = '<span class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" title="' . $team_name . '" itemprop="competitor" itemscope itemtype="http://schema.org/SportsTeam"><meta itemprop="name" content="' . $team_name . '">' . $logo . '</span>';
							else:
								$logo = '<span itemprop="competitor" itemscope itemtype="http://schema.org/SportsTeam"><meta itemprop="name" content="' . $team_name . '"></span>';
							endif;

							$logos[] = $logo;
						endforeach;
					endif;
					
					if ( 'day' === $calendar->orderby ):
						$event_group = get_post_meta( $event->ID, 'sp_day', true );
						if ( ! isset( $group ) || $event_group !== $group ):
							$group = $event_group;
							echo '<tr><th><strong class="sp-event-group-name">', __( 'Match Day', 'sportspress' ), ' ', $group, '</strong></th></tr>';
						endif;
					endif;
					?>
					<tr class="sp-row sp-post<?php echo ( $i % 2 == 0 ? ' alternate' : '' ); ?>" itemscope itemtype="http://schema.org/SportsEvent">
						<td>
							<?php do_action( 'sportspress_event_blocks_before', $event, $usecolumns ); ?>
							<?php echo implode( ' ', $logos ); ?>
							<time class="sp-event-date" datetime="<?php echo $event->post_date; ?>" itemprop="startDate" content="<?php echo mysql2date( 'Y-m-d\TH:iP', $event->post_date ); ?>" style="margin-bottom:0px !important;">
								<?php echo sp_add_link( get_the_time( get_option( 'date_format' ), $event ), $permalink, $link_events ).' - '.sp_add_link( '<span class="sp-result '.$event_status.'">' . implode( '</span> - <span class="sp-result">', apply_filters( 'sportspress_event_blocks_team_result_or_time', $results, $event->ID ) ) . '</span>', $permalink, $link_events ); ?>
							</time>
							<?php if ( $show_matchday ): $matchday = get_post_meta( $event->ID, 'sp_day', true ); if ( $matchday != '' ): ?>
								<div class="sp-event-matchday">(<?php echo '@'.$matchday; ?>)</div>
							<?php endif; endif; ?>
							<!--<h5 class="sp-event-results">
								<?php //echo sp_add_link( '<span class="sp-result '.$event_status.'">' . implode( '</span> - <span class="sp-result">', apply_filters( 'sportspress_event_blocks_team_result_or_time', $results, $event->ID ) ) . '</span>', $permalink, $link_events ); ?>
							</h5>-->
							
							
							
							<h4 class="sp-event-title" itemprop="name" style="margin-top:0px !important;">
								<?php echo sp_add_link( $event->post_title, $permalink, $link_events ); ?>
							</h4>
							<?php do_action( 'sportspress_event_blocks_after', $event, $usecolumns ); ?>

						</td>
					</tr>
					<?php
					$i++;
				endforeach;
				?>
			</tbody>
		</table>
	</div>
	<?php
	if ( $id && $show_all_events_link )
		echo '<div class="sp-calendar-link sp-view-all-link"><a href="' . get_permalink( $id ) . '">' . __( 'View all events', 'sportspress' ) . '</a></div>';
	?>
</div>
							
							
							
							
							
							
							
							
							
							
							
							
							
	

						
						</div>
						<a class="twitter-timeline" data-lang="en" data-height="300" href="https://twitter.com/PGCBLStallions?ref_src=twsrc%5Etfw">Tweets by PGCBLStallions</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
					</div>
				<?php endif; ?>
        </div>
           
       </div>      
            
            <div class="midcontentw">
                <a href="/fan-zone/birthday-group-parties/"><div class="fpparty">
                </div></a>
                <div class="fptickets">
                    <a href="/tickets/buy-tickets/"><div class="buytickets">
                    </div></a>
                    <a href="https://www.youtube.com/channel/UCLhlYnJj7LA6-8hcDB5kLtw/live"><div class="watchlive">
                    </div></a>
                </div>
                <div class="lowercontentmobile">
                    <div class="sponsors">
                        <?php echo do_shortcode('[slide-anything id="434"]'); ?>
                    </div>
                    <div class="fanoftheweek">
                    </div>
                </div>
                <a href="http://pgcbl.bbstats.pointstreak.com/standings.html?leagueid=1710&seasonid=32993" target=_blank><div class="fpstandings">
                    </div></a>
            </div>
            <div class="lowercontentw">
                <a href="/coming-soon-page/">
                <div class="fanoftheweek">
                </div></a>
				<div class="sponsors">
                    <?php echo do_shortcode('[slide-anything id="414"]'); ?>
                </div>
                
            </div>
        </div>
<?php
get_footer();
?>