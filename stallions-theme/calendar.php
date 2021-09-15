
<?php
/*
Template Name: calendar
Template Post Type: page
*/
// Page code here..
?>


        <link rel="stylesheet" type="text/css" href="\wp-content\themes\stallions-theme\fontawesome\css/all.css" media="all">
        <link rel="stylesheet" href="/wp-content/themes/stallions-theme/fontawesome/css/fontawesome.min.css">
<?php remove_filter ('the_content', 'wpautop'); ?> <?php // removes wp auto adding p and br ?>
<?php get_header(); ?>
<?php /* The Loop */ ?>
<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>    
    <!-- do stuff ... -->
    
    <?php endwhile; ?>
<?php endif; ?>
<meta content="width=device-width, initial-scale=1" name="viewport" />
<?php /* The Loop END */ ?>
<div class="pagew">
    
    <?php the_content(); ?>
<!--application/x-httpd-php event-calendar.php ( PHP script text )-->
<?php






if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb, $m, $monthnum, $year, $wp_locale;

$defaults = array(
    'id' => null,
    'status' => 'default',
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
    'player' => null,
    'initial' => true,
    'caption_tag' => 'caption',
    'show_all_events_link' => false,
    'override_global_date' => false,
);

extract( $defaults, EXTR_SKIP );

$calendar = new SP_Calendar( $id );
if ( $status != 'default' )
    $calendar->status = $status;
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
if ( $day != 'default' )
    $calendar->day = $day;
if ( $league )
    $calendar->league = $league;
if ( $season )
    $calendar->season = $season;
if ( $venue )
    $calendar->venue = $venue;
if ( $team )
    $calendar->team = $team;
if ( $player )
    $calendar->player = $player;
if ($override_global_date) {
    $year = gmdate('Y', current_time('timestamp'));
    $monthnum = gmdate('m', current_time('timestamp'));
}
$events = $calendar->data();

if ( empty( $events ) ) {
    $in = 'AND 1 = 0'; // False logic to prevent SQL error
} else {
    $event_ids = wp_list_pluck( $events, 'ID' );
    $in = 'AND ID IN (' . implode( ', ', $event_ids ) . ')';
}

// week_begins = 0 stands for Sunday
$week_begins = intval(get_option('start_of_week'));

// Get year and month from query vars
$year = isset( $_GET['sp_year'] ) ? $_GET['sp_year'] : $year;
$monthnum =  isset( $_GET['sp_month'] ) ? $_GET['sp_month'] : $monthnum;

// Let's figure out when we are
if ( !empty($monthnum) && !empty($year) ) {
    $thismonth = ''.zeroise(intval($monthnum), 2);
    $thisyear = ''.intval($year);
} elseif ( !empty($w) ) {
    // We need to get the month from MySQL
    $thisyear = ''.intval(substr($m, 0, 4));
    $d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
    $thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
} elseif ( !empty($m) ) {
    $thisyear = ''.intval(substr($m, 0, 4));
    if ( strlen($m) < 6 )
            $thismonth = '01';
    else
            $thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
} else {
    $thisyear = gmdate('Y', current_time('timestamp'));
    $thismonth = gmdate('m', current_time('timestamp'));
}

$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
$last_day = date('t', $unixmonth);

// Get the next and previous month and year with at least one post
$previous = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
    FROM $wpdb->posts
    WHERE post_date < '$thisyear-$thismonth-01'
    AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' )
    $in
        ORDER BY post_date DESC
        LIMIT 1");
$next = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
    FROM $wpdb->posts
    WHERE post_date > '$thisyear-$thismonth-{$last_day} 23:59:59'
    AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' )
    $in
        ORDER BY post_date ASC
        LIMIT 1");


/* translators: Calendar caption: 1: month name, 2: 4-digit year */
$calendar_caption = _x('%1$s %2$s', 'calendar caption', 'sportspress');
$calendar_output = '
<div class="ss-calendar-wrapper">
<table id="wp-calendar" class="ss-calendar ss-event-calendar ss-data-table">
<caption class="ss-table-caption">' . ( $caption_tag == 'caption' ? '' : '<' . $caption_tag . '>' ) . sprintf($calendar_caption, $wp_locale->get_month($thismonth), date('Y', $unixmonth)) . ( $caption_tag == 'caption' ? '' : '</' . $caption_tag . '>' ) . '</caption>
<thead>
<tr>';

$myweek = array();

for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
    $myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
}

foreach ( $myweek as $wd ) {
    $day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
    $wd = esc_attr($wd);
    $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
}

$calendar_output .= '
</tr>
</thead>

<tfoot>
<tr>';

if ( $previous ) {
    $calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="ss-previous-month"><a data-tooltip data-options="disable_for_touch:true" class="has-tooltip tip-right" href="' . add_query_arg( array( 'sp_year' => $previous->year, 'sp_month' => $previous->month ) ) . '" title="' . esc_attr( sprintf(_x('%1$s %2$s', 'calendar caption', 'sportspress'), $wp_locale->get_month($previous->month), date('Y', mktime(0, 0 , 0, $previous->month, 1, $previous->year)))) . '">&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) . '</a></td>';

    
} else {
    $calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
}

$calendar_output .= "\n\t\t".'<td class="pad"s>&nbsp;</td>';

if ( $next ) {
    $calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="ss-next-month"><a data-tooltip data-options="disable_for_touch:true" class="has-tooltip tip-left" href="' . add_query_arg( array( 'sp_year' => $next->year, 'sp_month' => $next->month ) ) . '" title="' . esc_attr( sprintf(_x('%1$s %2$s', 'calendar caption', 'sportspress'), $wp_locale->get_month($next->month), date('Y', mktime(0, 0 , 0, $next->month, 1, $next->year))) ) . '">' . $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;</a></td>';

   // echo add_query_arg( array( 'sp_year' => $next->year, 'sp_month' => $next->month ) );
} else {
    $calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
}

$calendar_output .= '
</tr>
</tfoot>

<tbody>
<tr>';
?>
<!-- MOBILE WEBSITE-->
<section class="MOBILE">
   <div class="datepicker-wrapper">
        <div class="p-datepicker">
            <div class="p-button-group" role="toolbar">
  
<!--button for prev month-->              
                     <?php if($previous){ ?>
                        <div class="p-button">
                        <a data-tooltip data-options="disable_for_touch:true" class="has-tooltip tip-right" 
                        href="<?php echo add_query_arg( array( 'sp_year' => $previous->year, 'sp_month' => $previous->month ) )?>"
                        title="<?php esc_attr( sprintf($wp_locale->get_month($previous->month), 
                                date('Y', mktime(0, 0 , 0, $previous->month, 1, $previous->year))))?>">
                            <?php 
                            /*
                             $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month));
                             echo $wp_locale;*/
                            ?>
                         <button class="p-button__button p-button__button--secondary p-datepicker__prev" aria-label="Previous Day">
                         <i class="fas fa-angle-left"></i>
                            </button>
                        </a></div>
                    <?php }
                    else{   ?>
                        <div class="p-button">
                         <button class="p-button__button p-button__button--secondary p-datepicker__prev" aria-label="Previous Day">
                                <i class="fas fa-angle-left"></i>
                            </button>
                        </div>
                    <?php }
                    ?>
<!--showing the month and the year-->
                <div class="p-button">
                    <button class="p-button__button p-button__button--secondary p-datepicker__current" aria-label="Select Date">
                           <?php  $today_month=  $wp_locale->get_month($thismonth);
                            $today_date= date('Y', $unixmonth);?>
                        <!--<h4><?php echo $today_month?></h4>
                        <h4><?php echo $today_date?></h4>-->
                        <div style="font-size: 15px;"> 
                                <?php echo $today_month.' ';
                                    echo $today_date;
                                    //echo $unixmonth;
                                    ?></div>
                        <!--<input class="p-datepicker__field" type="text" tabindex="-1">-->
                    </button>
                </div>
<!--button for next month-->
                    <?php 
                     
                    if($next){
                        $href_link =add_query_arg( array( 'sp_year' => $next->year, 'sp_month' => $next->month ) );
                    ?>
                        <div class="p-button">
                        <a data-tooltip data-options="disable_for_touch:true" 
                        class="has-tooltip tip-left" 
                        href="<?php echo $href_link ?>" 
                        title="<?php esc_attr( sprintf($wp_locale->get_month($next->month), 
                                            date('Y', mktime(0, 0 , 0, $next->month, 1, $next->year))))?>">
                        <button class="p-button__button p-button__button--secondary p-datepicker__next" aria-label="Next Day">
                            <i class="fas fa-angle-right"></i>
                        </button>
                    </a></div>
                    <?php
                    }
                    else{
                    ?>
                        <div class="p-button">
                        <button class="p-button__button p-button__button--secondary p-datepicker__next" aria-label="Next Day">
                            <i class="fas fa-angle-right"></i>
                        </button>
                        </div>
                    <?php }
                    ?>     
  <!--next day-->                  
                </div><!--p-button-group ends-->
               
            </div><!--p-datepicker ends-->
    </div><!--datepicker end-->

<?php
// Get days with posts
$dayswithposts = $wpdb->get_results("SELECT DAYOFMONTH(post_date), ID
    FROM $wpdb->posts WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00'
    AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' )
    $in
    AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59' ORDER BY post_date ASC", ARRAY_N

    );
	

	if ( $dayswithposts ) {
		$stallions_team = 0;
		foreach ( (array) $dayswithposts as $daywith ) {
			$game_var = new Game();
			$game_var->post_id = $daywith[1];
			$teams = (array) get_post_meta( $game_var->post_id, 'sp_team' );
			$game_var->home_away = get_post_meta($game_var->post_id, 'home_away')[0];
			$game_var->game_time = get_the_time( get_option('time_format'), $game_var->post_id );
			$game_var->special_event = get_post_meta($game_var->post_id, '_special_event');
			$game_var->event_description = get_post_meta($game_var->post_id, '_event_description');
			$game_var->opponent=0;
			if($game_var->stallions_team==0 && get_the_title($teams[0], "sp_team")=='Saugerties Stallions'){
				$game_var->stallions_team=$teams[0];
				//echo $stallions_team;
				$game_var->opponent=$teams[1];
			}
			if($game_var->stallions_team==0 && get_the_title($teams[1], "sp_team")=='Saugerties Stallions'){
				$game_var->stallions_team=$teams[1];
				//echo $stallions_team;
				$game_var->opponent=$teams[0];
			}
			
			$result = (array)get_post_meta($game_var->post_id, 'sp_results');
			
			// TODO: Fix
			//echo var_dump($result);
			//$stallions_result= (($result[0])[$stallions_team]['outcome'][0]);
			//..$stallions_result= "T";
			//$games_score_1=(($result[0])[$stallions_team]['r']);
			
			$game_var->home_score=(get_post_meta($game_var->post_id, "home_score"));
			//$games_score_2=(($result[0])[$opponent]['r']);
			
			$game_var->away_score=(get_post_meta($game_var->post_id, "away_score"));
			//echo $games_score_1;
			//echo $games_score_2;
			
			//TODO: WHAT THE FUCK IS THIS SHIT?!	
			if(/*isset($daywithpost) && */count( $daywithpost[$daywith[0]])!=0){
			//if( count( $daywithpost[$daywith[0]])!=0){
				$game2withpost[$daywith[0]][0]=$game_var;
				$day2withpost[ $daywith[0] ][] = $game_var->post_id;
				$time2withpost [$daywith[0]]=$game_var->game_time;
				$teamwithpost[$daywith[0]][2] = $teams[0];
				$teamwithpost[$daywith[0]][3] = $teams[1];
				$homeaway2withpost[$daywith[0]]=$game_var->home_away;
				$specialevent2withpost[$daywith[0]]=$game_var->special_event;
				$specialeventdes2withpost[$daywith[0]]=$game_var->event_description ;
				//$result2withpost[$daywith[0]][0]=$stallions_result;
				//$result2withpost[$daywith[0]][1]=$games_score_1;
				//$result2withpost[$daywith[0]][2]=$games_score_2;
				$opponent2withpost[$daywith[0]]=$game_var->opponent;    
			}

			//if(count( $daywithpost[$daywith[0]])==0){
			if(/*isset($daywithpost) && */count( $daywithpost[$daywith[0]])==0){
				$gamewithpost[$daywith[0]][0]= $game_var;
				$daywithpost[ $daywith[0] ][] = $game_var->post_id;
				$timewithpost[$daywith[0]]=$game_var->game_time;
				$teamwithpost[$daywith[0]][0] = $teams[0];
				$teamwithpost[$daywith[0]][1] = $teams[1];
				$homeawaywithpost[$daywith[0]]=$game_var->home_away;
				$specialeventwithpost[$daywith[0]]=$game_var->special_event;
				$specialeventdeswithpost[$daywith[0]]=$game_var->event_description ;
				//$resultwithpost[$daywith[0]][0]=$stallions_result;
				//$resultwithpost[$daywith[0]][1]=$games_score_1;
				//$resultwithpost[$daywith[0]][2]=$games_score_2;
				$opponentwithpost[$daywith[0]]=$opponent;
			}   
			
				//echo '<pre>';
				//var_dump($homeawaywithpost);
				//echo '</pre>';
		}
	} 
	else {
		$daywithpost = array();
		$day2withpost = array();
		$timewithpost = array();
		$time2withpost = array();
		$teamwithpost = array();
		$homeawaywithpost = array();
		$specialeventwithpost=array();
		$specialeventdeswithpost=array();

		$homeaway2withpost = array();
		$specialevent2withpost=array();
		$specialeventdes2withpost=array();
		
		$resultwithpost=array();
		$result2withpost=array();

		$opponentwithpost=array();
		$opponent2withpost=array();
	}

 
if ( array_key_exists( 'HTTP_USER_AGENT', $_SERVER ) && preg_match( '/(MSIE|camino|safari)/', $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {
    $ak_title_separator = "\n";
} else {
    $ak_title_separator = ', ';
}

$ak_titles_for_day = array();
$ak_post_titles = $wpdb->get_results("SELECT ID, post_title, post_date, DAYOFMONTH(post_date) as dom "
    ."FROM $wpdb->posts "
    ."WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00' "
    ."AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59' "
    ."AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' ) "
    ."$in"
);
if ( $ak_post_titles ) {
    foreach ( (array) $ak_post_titles as $ak_post_title ) {

            /** This filter is documented in wp-includes/post-template.php */
            $post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) . ' @ ' . apply_filters( 'sportspress_event_time', date_i18n( get_option( 'time_format' ), strtotime( $ak_post_title->post_date ) ), $ak_post_title->ID ) );

            if ( empty($ak_titles_for_day['day_'.$ak_post_title->dom]) )
                $ak_titles_for_day['day_'.$ak_post_title->dom] = '';
            if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
                $ak_titles_for_day["$ak_post_title->dom"] = $post_title;
            else
                $ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . $post_title;
    }
}

// See how much we should pad in the beginning
$pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
if ( 0 != $pad )
    $calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

$daysinmonth = intval(date('t', $unixmonth));
$rowcount= 0;
$lastrow = false;
$firstdayofweek = 0;
?>
<div class="list-mode" id="MOBILE">
<?php
$_odd = true; // this is for the mobile schedule page alternating backgroundcolor
for ( $day = 1; $day <= $daysinmonth; ++$day ) {
    if ( isset($newrow) && $newrow ){
        $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
        ?>
        <!--border in starting new week-->
        <div class="list_new_week" id="MOBILE">

        </div>
<?php
    }
    $newrow = false;
    if($daysinmonth-$firstdayofweek<=6){
        $lastrow=true;
    }

    $day_has_posts = array_key_exists($day, $daywithpost);
    $td_properties = '';

    if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) )
        $td_properties .= ' id="today" class="ss-highlight"';

    if ( $day_has_posts )
        $td_properties .= ' itemscope itemtype="http://schema.org/SportsEvent"';

    $calendar_output .= '<td' . $td_properties . ' ';
    //echo $homeawaywithpost[$day][0];
	//echo "<script>alert(\"".$gamewithpost[$day][0]->home_away."\");</script>";
    if($gamewithpost[$day][0]->home_away=="home"){
        $calendar_output .= 'style="background-color:#e6be53"';
    }
    if($gamewithpost[$day][0]->home_away=="away"){
        $calendar_output .= 'style="background-color:#a6a6a6"';
    }
    $calendar_output .= '>';
    if ( $day_has_posts ) {// any posts today?
          ?>
          <!--pop up-->
        <!--<div class="tooltip-wrap">
          <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Ftwitter.com%2Fpgcblstallions&psig=AOvVaw0ERlCAn8iGwTRGPJZRQ-eW&ust=1615577661664000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCODVr_f9qO8CFQAAAAAdAAAAABAD" alt="Some Image" />
          <div class="tooltip-content">
            <p>Here is some content for the tooltip</p>
          </div> 
        </div>
        -->
<?php
       //if($specialeventwithpost[$day][0]){ echo "*";}
        //hover popup content
       //check whether it's future event
       $popup_appear= false;
       if($thisyear >= gmdate('Y', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) 
       && $day >= gmdate('j', current_time('timestamp'))){
           $popup_appear=true;
       }
       else if($thismonth > gmdate('m', current_time('timestamp'))){
           $popup_appear=true;
       }
       else{
           $popup_appear=false;
       }
       
           
       if($popup_appear ){

       //first row
        if($rowcount==0){
                $style='style="bottom:-160px;';
        }
        else if($lastrow){
            $style='style="top:-155px;';
        }
        else{
            $style='style="top:-80px;';
        }
        
        
       //first column(Monday)
        if(0 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins)){
                    $style.='left:0px; ';
       }
       //last column(Sunday)
        else if(6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins)){
                    $style.='left:-160px; ';
       }
        //other normal
       else{
            $style.='left:-80px; ';

       }


        if(count($teamwithpost[$day])>2){ //if there are two games on the same day //TODO: FIX
			//if($timewithpost[$day]<$time2withpost[$day]){ //team 1 -> team2
			if($gamewithpost[$day][0]->game_time < $game2withpost[$day][0]->game_time){ //team 1 -> team2
				$calendar_output .= '<a data-tooltip data-options="disable_for_touch:true" class="has-tip" href="' . 
				( sizeof( $daywithpost[ $day ] ) > 1 ? add_query_arg( array( 'post_type' => 'sp_event' ), get_day_link( $thisyear, $thismonth, $day ) ) . 
				'" title="' . sprintf( __( '%s events', 'sportspress' ),
				( sizeof( $daywithpost[ $day ] ) ) ) : 
					get_post_permalink( $gamewithpost[$day][0]->post_id, false, true ) . 
					'" title="' . esc_attr( $ak_titles_for_day[ $day ] ) ) .
					"\" itemprop=\"url\">";


				$calendar_output .= '<div class="tooltip-wrap">
				  <div class="tooltip-content"';
				$calendar_output .= $style;
				  
				  
				if($gamewithpost[$day][0]->home_away=="home"){
				$calendar_output .= 'background-color:#e6be53;">';
				}
				if($gamewithpost[$day][0]->home_away=="away"){
					$calendar_output .= 'background-color:#a6a6a6;">';
				}

			   //date in popup
			   $calendar_output .= '<div class="td_date">'.$day.'</div>';
			   //logos in popup
			  // $calendar_output.= '<p>Here is some content for the tooltip</p>';
			   $calendar_output.=  '<div class="popup_team_thumbnail">'.get_the_post_thumbnail(  $teamwithpost[$day][0], 'calendar-icon' );
			   $calendar_output.=  'vs';
				 
			   $calendar_output.=  get_the_post_thumbnail(  $teamwithpost[$day][1], 'calendar-icon' ).'</div>';
			   $calendar_output.= '<div class="calendar_event_time">'. $timewithpost[$day].'</div>';
				   $calendar_output.='</a>';
			   //second game
			   $calendar_output .= '<a data-tooltip data-options="disable_for_touch:true" class="has-tip" href="' . 
				( sizeof( $day2withpost[ $day ] ) > 1 ? add_query_arg( array( 'post_type' => 'sp_event' ), get_day_link( $thisyear, $thismonth, $day ) ) . 
				'" title="' . sprintf( __( '%s events', 'sportspress' ),
				( sizeof( $day2withpost[ $day ] ) ) ) : 
					get_post_permalink( $day2withpost[ $day ][0], false, true ) . 
					'" title="' . esc_attr( $ak_titles_for_day[ $day ] ) ) .
					"\" itemprop=\"url\">";
					
			   $calendar_output.=  '<div class="popup_team_thumbnail">'.get_the_post_thumbnail(  $teamwithpost[$day][2], 'calendar-icon' );
			   $calendar_output.=  'vs';
				 
			   $calendar_output.=  get_the_post_thumbnail(  $teamwithpost[$day][3], 'calendar-icon' ).'</div>';
			   $calendar_output.= '<div class="calendar_event_time">'. $time2withpost[$day].'</div>';
			   //Buy Ticket button
			   
				if($game2withpost[$day]->home_away=="home" || $gamewithpost[$day]->home_away=="home" ){
					$calendar_output.=  '<div class="popover-tickets-link">
					<span class="large-ticket-link-with-button">
					<div class="p-button">
						<a href="'.get_post_permalink( $day2withpost[ $day ][0], false, true ).'" class="p-button__link" data-popout="" target="_blank">
						<button class="p-button__button">
							<span class="p-button__text">Buy Tickets</span>
						</button>
						</a>
					</div></span></div>';
				}
				 
				//Special Event
				if($gamewithpost[$day]->special_event=="yes" || $game2withpost[$day]->special_event=="yes"){
					
					$description = $specialeventdes2withpost[$day][0];
					  $calendar_output.='<div class="popover_special_event"><i class="fas fa-asterisk" style="float:left"></i>';
					  $calendar_output.='<div class="popover-special_event_description">
						 <a>Speical Event: </a>
						 <br>
						 <span class="description" title="undefined">'.$description.'</span>
						 
						 </div></div>';
																	   
				  }
				  $calendar_output.='</a>';
			}//time1->time2
			else{   //time2->time1
			
							$calendar_output .= '<a data-tooltip data-options="disable_for_touch:true" class="has-tip" href="' . 
				( sizeof( $day2withpost[ $day ] ) > 1 ? add_query_arg( array( 'post_type' => 'sp_event' ), get_day_link( $thisyear, $thismonth, $day ) ) . 
				'" title="' . sprintf( __( '%s events', 'sportspress' ),
				( sizeof( $day2withpost[ $day ] ) ) ) : 
					get_post_permalink( $day2withpost[ $day ][0], false, true ) . 
					'" title="' . esc_attr( $ak_titles_for_day[ $day ] ) ) .
					"\" itemprop=\"url\">";


				$calendar_output .= '<div class="tooltip-wrap">
				  <div class="tooltip-content"';
				$calendar_output .= $style;
				  
				  
				if($game2withpost[$day]->home_away=="home"){
				$calendar_output .= 'background-color:#e6be53;">';
				}
				if($game2withpost[$day]->home_away=="away"){
					$calendar_output .= 'background-color:#a6a6a6;">';
				}

			   //date in popup
			   $calendar_output .= '<div class="td_date">'.$day.'</div>';
			   //logos in popup
			  // $calendar_output.= '<p>Here is some content for the tooltip</p>';
			   $calendar_output.=  '<div class="popup_team_thumbnail">'.get_the_post_thumbnail(  $teamwithpost[$day][2], 'calendar-icon' );
			   $calendar_output.=  'vs';
				 
			   $calendar_output.=  get_the_post_thumbnail(  $teamwithpost[$day][3], 'calendar-icon' ).'</div>';
			   $calendar_output.= '<div class="calendar_event_time">'. $time2withpost[$day].'</div>';

			   //second game
			   $calendar_output .= '<a data-tooltip data-options="disable_for_touch:true" class="has-tip" href="' . 
				( sizeof( $daywithpost[ $day ] ) > 1 ? add_query_arg( array( 'post_type' => 'sp_event' ), get_day_link( $thisyear, $thismonth, $day ) ) . 
				'" title="' . sprintf( __( '%s events', 'sportspress' ),
				( sizeof( $daywithpost[ $day ] ) ) ) : 
					get_post_permalink( $daywithpost[ $day ][0], false, true ) . 
					'" title="' . esc_attr( $ak_titles_for_day[ $day ] ) ) .
					"\" itemprop=\"url\">";
					
			   $calendar_output.=  '<div class="popup_team_thumbnail">'.get_the_post_thumbnail(  $teamwithpost[$day][0], 'calendar-icon' );
			   $calendar_output.=  'vs';
				 
			   $calendar_output.=  get_the_post_thumbnail(  $teamwithpost[$day][2], 'calendar-icon' ).'</div>';
			   $calendar_output.= '<div class="calendar_event_time">'. $timewithpost[$day].'</div>';
			   //Buy Ticket button
			   
				if($game2withpost[$day]->home_away=="home" || $gamewithpost[$day]->home_away=="home" ){
					$calendar_output.=  '<div class="popover-tickets-link">
					<span class="large-ticket-link-with-button">
					<div class="p-button">
						<a href="'.get_post_permalink( $daywithpost[ $day ][0], false, true ).'" class="p-button__link" data-popout="" target="_blank">
						<button class="p-button__button">
							<span class="p-button__text">Buy Tickets</span>
						</button>
						</a>
					</div></span></div>';
				}
				 
				//Special Event
				if($gamewithpost[$day]->special_event=="yes" || $game2withpost[$day]->special_event=="yes"){
					
					$description = $specialeventdes2withpost[$day][0];
					  $calendar_output.='<div class="popover_special_event"><i class="fas fa-asterisk" style="float:left"></i>';
					  $calendar_output.='<div class="popover-special_event_description">
						 <a>Speical Event: </a>
						 <br>
						 <span class="description" title="undefined">'.$description.'</span>
						 
						 </div></div>';
																	   
				  }
				  $calendar_output.='</a>';
			}
         }//multiple games 
        else{ //single game
				  $calendar_output .= '<a data-tooltip data-options="disable_for_touch:true" class="has-tip" href="' . 
			( sizeof( $daywithpost[ $day ] ) > 1 ? add_query_arg( array( 'post_type' => 'sp_event' ), get_day_link( $thisyear, $thismonth, $day ) ) . 
			'" title="' . sprintf( __( '%s events', 'sportspress' ),
			( sizeof( $daywithpost[ $day ] ) ) ) : 
				get_post_permalink( $daywithpost[ $day ][0], false, true ) . 
				'" title="' . esc_attr( $ak_titles_for_day[ $day ] ) ) .
				"\" itemprop=\"url\">";


        $calendar_output .= '<div class="tooltip-wrap">
          <div class="tooltip-content"';
        $calendar_output .= $style;
          
          
        if($gamewithpost[$day]->home_away=="home"){
			$calendar_output .= 'background-color:#e6be53;">';//gold
        }
        else if($gamewithpost[$day]->home_away=="away"){
            $calendar_output .= 'background-color:#a6a6a6;">';//gray
        }

       //date in popup
       $calendar_output .= '<div class="td_date">'.$day.'</div>';
       //logos in popup
      // $calendar_output.= '<p>Here is some content for the tooltip</p>';
       $calendar_output.=  '<div class="popup_team_thumbnail">'.get_the_post_thumbnail(  $teamwithpost[$day][0], 'calendar-icon' );
       $calendar_output.=  'vs';
         
       $calendar_output.=  get_the_post_thumbnail(  $teamwithpost[$day][1], 'calendar-icon' ).'</div>';
       $calendar_output.= '<div class="calendar_event_time">'. $timewithpost[$day].'</div>';
       //Buy Ticket button
       
        if($homeawaywithpost[$day][0]=="home"){
            $calendar_output.=  '<div class="popover-tickets-link">
            <span class="large-ticket-link-with-button">
            <div class="p-button">
                <a href="'.get_post_permalink( $daywithpost[ $day ][0], false, true ).'" class="p-button__link" data-popout="" target="_blank">
                <button class="p-button__button">
                    <span class="p-button__text">Buy Tickets</span>
                </button>
                </a>
            </div></span></div>';
        }
         
        //Special Event
        if($specialeventwithpost[$day][0]=="yes"){
            
            $description = $specialeventdeswithpost[$day][0];
              $calendar_output.='<div class="popover_special_event"><i class="fas fa-asterisk" style="float:left"></i>';
              // <a href="/yankees/tickets/promotions" class="popover-promo-header">Speical Event</a>
              //<a class="label" href="/yankees/tickets/promotions">Mastercard Preferred Pricing</a>
              //<span class="label" href=""></span>
              $calendar_output.='<div class="popover-special_event_description">
                 <a>Speical Event: </a>
                 <br>
                 <span class="description" title="undefined">'.$description.'</span>
                 
                 </div></div>';
                                                               
          }
          
       
          $calendar_output.='</a>';
        }//single game

    

    }
 //hover popup ends
        //date 
        $calendar_output.= '</div><div class="td_date">'.$day.'</div>';
        ?>
           <div class="list-mode-table-wrapper" id="MOBILE">
                <?php 
                    if($_odd){
                 ?> 
                 <table class="list-mode-game table-odd">
                 <?php 
                 $_odd=false;     
                }//odd
                else{
                ?>
                 <table class="list-mode-game table-even">

                <?php   
                $_odd=true; 
                }
                ?>
                    <tbody>
                        <tr class="primary-row-tr">
                            <td class="date-td">
                                <div class="month-date">
                                    <!-- format: Mar 19-->
                                    <?php 
                                          //                                        echo date('M', strtotime($thismonth . '01'));
                                        //echo $day;                        
                                        
                                        $unixTimestamp = strtotime($thisyear.'-'.$thismonth.'-'.$day);
                                         
                                        echo date('M', $unixTimestamp);
                                        echo $day;
                                    ?>
                                </div>
                                <div class="weekday">
                                    <?php 
                                         $unixTimestamp = strtotime($thisyear.'-'.$thismonth.'-'.$day);
                                         
                                         $nameOfDay = date('D', $unixTimestamp);
                                         echo $nameOfDay;
                                         //$weekday = date("l", $unixTimestamp);
                                         //echo $weekday;
                                    ?>
                                </div>
                                <div clsas="game-description-note">

                                </div>
                            </td>
                            <?php 
                            if($gamewithpost[$day]->home_away=="home"){
                            ?>
                            <td class="matchup-td">
                                <div class="matchup">
                                    <div class="xref-home-team-wrapper">
                                        <div class="xref-home-logo-wrapper">
       
                                        </div>
                                    </div>
                                    <!--xref-away-team-wrapper ends-->
                                    <!-- at-or-us -->
                                    <div class="at-or-us">
                                        <div class="ss-against-note"></div>
                                        <div class="vs">vs.</div>
                                    </div>
                                    <!-- at-or-us ends-->
                                    <!--opponent-logo starts-->
                                    <div class="opponent-logo-wrapper">
                                     <?php 
										//selects which is the home or away team
                                        if(get_the_title($teamwithpost[$day][0])=="Saugerties Stallions"){
                                            $opponent_name=get_the_title($teamwithpost[$day][1]);
                                             $url=get_the_post_thumbnail_url(  $teamwithpost[$day][1]);
                                             $away_score=$gamewithpost[$day]->away_score;
                                             $home_score=$gamewithpost[$day]->home_score;
                                             // $url=get_the_post_thumbnail_url(  $teamwithpost[$day][1], 'calendar-icon');
                                             ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                        else{
                                            $opponent_name=get_the_title($teamwithpost[$day][0]);
                                            $url= get_the_post_thumbnail_url($teamwithpost[$day][0]);
                                            $away_score=$gamewithpost[$day]->away_score;
                                            $home_score=$gamewithpost[$day]->home_score;
                                            ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                    ?>  
                                    </div>
                                    <!--opponent-logo ends-->
                                    <div class="opponent-name"><?php echo $opponent_name ?></div>
                                    <!--right now we don't have the tricode
                                    <div class="opponent-tricode"></div> 
                                    -->                      
                                    
                                    <a class="non-game-event-logo-link" href style="display:none;">
                                        <img class="non-game-event-logo">
                                    </a>
                                </div>
                                <!--match up div ends-->
                                <!--special event-->
                                <?php
                                if($gamewithpost[$day]->special_event){//FUTURE game displays special events
                                    ?>
                                
                                <div class="non-game-event-title"></div>
                                <div class="special-event">
                                    <div class="icon-wrapper" title="Special Event">
                                        <i class="fas fa-asterisk fa-sm"></i>
                                    </div>
                                    <div class="label" title="Special Event">Special Event</div>
                                </div>
                                
                                <?php 
                                }
                                ?>
                                <!--special event ends-->
                            </td>
                            <!--match up -td ends-->
                            <!--mega column-->
                            <td class="megacolumn-td-small">
                                
                                <?php
                                if(!$popup_appear){//past game //HOME
                                    ?>
                                    <!--<a class="score" href="">
                                        <span class="outcome">
                                            <?php 
                                                $output;
                                                //echo substr($resultwithpost[$day][0],0);
                                                /* if($resultwithpost[$day][0]=='win')
                                                    $output ="W";
                                                if($resultwithpost[$day][0]=='loss')
                                                    $output ='L';  
                                                if($resultwithpost[$day][0]=='tie')
                                                    $output ='T';  */
												$output = $gamewithpost[$day].getResult();
                                                echo $output.', ';
                                            ?>
                                            
                                            <?php 
                                                //away_score
                                                echo $home_score;
                                                echo ' - ';
                                                echo $away_score;
                                            ?>
                                        </span>
                                        <span class="left-score-tricode"></span>
                                        <span class="left-score"></span>
                                        <span class="hyphen"></span>
                                        <span class="right-score-tricode"></span>
                                        <span class="right-score"></span>
                                    </a>-->
                                    <!--a for score ended-->

                                <?php
                                }//if(!$popup_appear) ends
                                else{//the future games
                                ?>
                                    <a href="/<?php //TODO: LINK FIX ?>" class="time">
                                        <div class="primary-time">
                                            <?php 
                                                echo $gamewithpost[$day]->game_time;
                                            ?>
                                        </div>
                                    </a>                                
                                <?php 
                                }//future games end
                                ?>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="tickets-arrows-wrap-td-small">
                                <?php 
                                    if($popup_appear){
                                ?>
                                    <a class="small-ticket-link icon-wrapper tickets" 
                                    href="<?php echo get_post_permalink( $daywithpost[ $day ][0], false, true ); ?>" 
                                    target="_blank">
                                        <i class="fas fa-ticket-alt fa-ticket-mobile"></i>
                                    </a>
                                <?php 
                                    }
                                ?>
                            </td>
                            </td>
                            <!--HOME ENDS-->
                            <!--AWAY-->
                            <?php 
                            }
                            if($gamewithpost[$day]->home_away=="away"){
                            ?>
                            <td class="matchup-td">
                                <div class="matchup">
                                    <div class="xref-away-team-wrapper">
                                        <div class="xref-away-logo-wrapper">
       
                                        </div>
                                    </div>
                                    <!--xref-away-team-wrapper ends-->
                                    <!-- at-or-us -->
                                    <div class="at-or-us">
                                        <div class="ss-against-note"></div>
                                        <div class="at">@</div>
                                    </div>
                                    <!-- at-or-us ends-->
                                    <!--opponent-logo starts-->
                                    <div class="opponent-logo-wrapper">
                                     <?php 
                                        if(get_the_title($teamwithpost[$day][0])=="Saugerties Stallions"){
                                            $opponent_name=get_the_title($teamwithpost[$day][1]);
                                             $url=get_the_post_thumbnail_url(  $teamwithpost[$day][1]);
                                             // $url=get_the_post_thumbnail_url(  $teamwithpost[$day][1], 'calendar-icon');
                                             ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                        else{
                                            $opponent_name=get_the_title($teamwithpost[$day][0]);
                                            $url= get_the_post_thumbnail_url($teamwithpost[$day][0]);
                                            ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                    ?>  
                                    </div>
                                    <!--opponent-logo ends-->
                                    <div class="opponent-name"><?php echo $opponent_name ?></div>
                                    <!--right now we don't have the tricode
                                    <div class="opponent-tricode"></div> 
                                    -->                      
                                    
                                    <a class="non-game-event-logo-link" href style="display:none;">
                                        <img class="non-game-event-logo">
                                    </a>
                                </div>
                                <!--match up div ends-->
                                
                                <!--special event-->
                                <?php
                                if( $specialeventwithpost[$day][0]=="yes"){//FUTURE game displays special events
                                    ?>
                                
                                <div class="non-game-event-title"></div>
                                <div class="special-event">
                                    <div class="icon-wrapper" title="Special Event">
                                        <i class="fas fa-asterisk"></i>
                                    </div>
                                    <div class="label" title="Special Event">Special Event</div>
                                </div>
                                
                                <?php 
                                }
                                ?>
                                <!--special event ends-->
                                
                            </td>
                            <!--match up -td ends-->
                            <!--mega column-->
                            <td class="megacolumn-td-small">
                                
                                <?php
                                //echo 'is this working?';
                                if(!$popup_appear){//past game // AWAY
                                    ?>
                                    <!--<a class="score" href="">
                                        <span class="outcome">
                                            <?php 
                                                $output;
                                                /* if($resultwithpost[$day][0]=='win')
                                                    $output ="W";
                                                if($resultwithpost[$day][0]=='loss')
                                                    $output ='L';  
                                                if($resultwithpost[$day][0]=='tie')
                                                    $output ='T';   */   
												$output = $daywithpost[$day].getResult();
                                                echo $output.', ';
                                            ?>
                                            <?php 
                                                //away_score
                                                echo $resultwithpost[$day][1];
                                                echo ' - ';
                                                echo $resultwithpost[$day][2];
                                            ?>
                                        </span>
                                        <span class="left-score-tricode"></span>
                                        <span class="left-score"></span>
                                        <span class="hyphen"></span>
                                        <span class="right-score-tricode"></span>
                                        <span class="right-score"></span>
                                    </a>-->
                                    <!--a for score ended-->

                                <?php
                                }//if(!$popup_appear) ends
                                else{
                                ?>
                                    <a href="/" class="time">
                                        <div class="primary-time">
                                            <?php 
                                                echo $timewithpost[$day];
                                            ?>
                                        </div>
                                    </a>                                
                                <?php 
                                }
                                ?>
                            </td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="tickets-arrows-wrap-td-small"></td>
                            <?php 
                                }//AWAY ENDS 
                            ?>
                        </tr>   
                    </tbody>
                </table>
            </div><!--list-mode-table-wrapper" id="MOBILE" ENDS-->
            <?php 
            if(count($teamwithpost[$day])>2){ //if there are more than one game at that day, add one more div block for that game?>
               <div class="list-mode-table-wrapper" id="MOBILE">
                <?php 
                    if($_odd){
                 ?> 
                 <table class="list-mode-game table-odd">
                 <?php 
                 $_odd=false;     
                }//odd
                else{
                ?>
                 <table class="list-mode-game table-even">

                <?php   
                $_odd=true; 
                }
                ?>
                    <tbody>
                        <tr class="primary-row-tr">
                            <td class="date-td">
                                <div class="month-date">
                                    <!-- format: Mar 19-->
                                    <?php 
                                          //                                        echo date('M', strtotime($thismonth . '01'));
                                        //echo $day;                        
                                        
                                        $unixTimestamp = strtotime($thisyear.'-'.$thismonth.'-'.$day);
                                         
                                        echo date('M', $unixTimestamp);
                                        echo $day;
                                    ?>
                                </div>
                                <div class="weekday">
                                    <?php 
                                         $unixTimestamp = strtotime($thisyear.'-'.$thismonth.'-'.$day);
                                         
                                         $nameOfDay = date('D', $unixTimestamp);
                                         echo $nameOfDay;
                                         //$weekday = date("l", $unixTimestamp);
                                         //echo $weekday;
                                    ?>
                                </div>
                                <div clsas="game-description-note">

                                </div>
                            </td>
                            <?php 
                            if($game2withpost[$day]->home_away=="home"){
                            ?>
                            <td class="matchup-td">
                                <div class="matchup">
                                    <div class="xref-home-team-wrapper">
                                        <div class="xref-home-logo-wrapper">
       
                                        </div>
                                    </div>
                                    <!--xref-away-team-wrapper ends-->
                                    <!-- at-or-us -->
                                    <div class="at-or-us">
                                        <div class="ss-against-note"></div>
                                        <div class="vs">vs.</div>
                                    </div>
                                    <!-- at-or-us ends-->
                                    <!--opponent-logo starts-->
                                    <div class="opponent-logo-wrapper">
                                     <?php 
                                        if(get_the_title($teamwithpost[$day][2])=="Saugerties Stallions"){
                                            $opponent_name=get_the_title($teamwithpost[$day][3]);
                                             $url=get_the_post_thumbnail_url(  $teamwithpost[$day][3]);
                                             $away_score=$result2withpost[$day][1];
                                             $home_score=/*$result2withpost[$day][0]*/get_post_meta(get_the_ID(),'home_score');
                                             // $url=get_the_post_thumbnail_url(  $teamwithpost[$day][1], 'calendar-icon');
                                             ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                        else{
                                            $opponent_name=get_the_title($teamwithpost[$day][2]);
                                            $url= get_the_post_thumbnail_url($teamwithpost[$day][2]);
                                            $away_score=$game2withpost[$day]->away_score;
                                            $home_score=$game2withpost[$day]->home_score;
                                            ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                    ?>  
                                    </div>
                                    <!--opponent-logo ends-->
                                    <div class="opponent-name"><?php echo $opponent_name ?></div>
                                    <!--right now we don't have the tricode
                                    <div class="opponent-tricode"></div> 
                                    -->                      
                                    
                                    <a class="non-game-event-logo-link" href style="display:none;">
                                        <img class="non-game-event-logo">
                                    </a>
                                </div>
                                <!--match up div ends-->
                                <!--special event-->
                                <?php
                                if( $specialevent2withpost[$day][0]=="yes"){//FUTURE game displays special events
                                    ?>
                                
                                <div class="non-game-event-title"></div>
                                <div class="special-event">
                                    <div class="icon-wrapper" title="Special Event">
                                        <i class="fas fa-asterisk fa-sm"></i>
                                    </div>
                                    <div class="label" title="Special Event">Special Event</div>
                                </div>
                                
                                <?php 
                                }
                                ?>
                                <!--special event ends-->
                            </td>
                            <!--match up -td ends-->
                            <!--mega column-->
                            <td class="megacolumn-td-small">
                                
                                <?php
                                if(!$popup_appear){//past game //HOME
                                    ?>
                                    <!--<a class="score" href="">
                                        <span class="outcome">
                                            <?php 
                                                $output;
                                                //echo substr($resultwithpost[$day][0],0);
                                                /* if($result2withpost[$day][0]=='win')
                                                    $output ="W";
                                                if($result2withpost[$day][0]=='loss')
                                                    $output ='L';  
                                                if($result2withpost[$day][0]=='tie')
                                                    $output ='T'; */ 
												$output=$game2withpost[$day].getResult();
                                                echo $output.', ';
                                            ?>
                                            
                                            <?php 
                                                //away_score
                                                echo $home_score;
                                                echo ' - ';
                                                echo $away_score;
                                            ?>
                                        </span>
                                        <span class="left-score-tricode"></span>
                                        <span class="left-score"></span>
                                        <span class="hyphen"></span>
                                        <span class="right-score-tricode"></span>
                                        <span class="right-score"></span>
                                    </a>-->
                                    <!--a for score ended-->

                                <?php
                                }//if(!$popup_appear) ends
                                else{//the future games
                                ?>
                                    <a href="/" class="time">
                                        <div class="primary-time">
                                            <?php 
                                                echo $time2withpost[$day];
                                            ?>
                                        </div>
                                    </a>                                
                                <?php 
                                }//future games end
                                ?>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="tickets-arrows-wrap-td-small">
                                <?php 
                                    if($popup_appear){
                                ?>
                                    <a class="small-ticket-link icon-wrapper tickets" 
                                    href="<?php echo get_post_permalink( $daywithpost[ $day ][0], false, true );//TODO: Check this ?>" 
                                    target="_blank">
                                        <i class="fas fa-ticket-alt fa-ticket-mobile"></i>
                                    </a>
                                <?php 
                                    }
                                ?>
                            </td>
                            </td>
                            <!--HOME ENDS-->
                            <!--AWAY-->
                            <?php 
                            }
                            if($game2withpost->home_away=="away"){
                            ?>
                            <td class="matchup-td">
                                <div class="matchup">
                                    <div class="xref-away-team-wrapper">
                                        <div class="xref-away-logo-wrapper">
       
                                        </div>
                                    </div>
                                    <!--xref-away-team-wrapper ends-->
                                    <!-- at-or-us -->
                                    <div class="at-or-us">
                                        <div class="ss-against-note"></div>
                                        <div class="at">@</div>
                                    </div>
                                    <!-- at-or-us ends-->
                                    <!--opponent-logo starts-->
                                    <div class="opponent-logo-wrapper">
                                     <?php 
                                        if(get_the_title($teamwithpost[$day][2])=="Saugerties Stallions"){
                                            $opponent_name=get_the_title($teamwithpost[$day][3]);
                                             $url=get_the_post_thumbnail_url(  $teamwithpost[$day][3]);
                                             $away_score=$game2withpost[$day]->away_score;
                                             $home_score=$game2withpost[$day]->home_score;
                                             // $url=get_the_post_thumbnail_url(  $teamwithpost[$day][1], 'calendar-icon');
                                             ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                        else{
                                            $opponent_name=get_the_title($teamwithpost[$day][2]);
                                            $url= get_the_post_thumbnail_url($teamwithpost[$day][2]);
                                            $away_score=$game2withpost[$day]->away_score;
                                            $home_score=$game2withpost[$day]->home_score;
                                            ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                    ?>  
                                    </div>
                                    <!--opponent-logo ends-->
                                    <div class="opponent-name"><?php echo $opponent_name ?></div>
                                    <!--right now we don't have the tricode
                                    <div class="opponent-tricode"></div> 
                                    -->                      
                                    
                                    <a class="non-game-event-logo-link" href style="display:none;">
                                        <img class="non-game-event-logo">
                                    </a>
                                </div>
                                <!--match up div ends-->
                                
                                <!--special event-->
                                <?php
                                if($popup_appear){//FUTURE game displays special events
                                    ?>
                                
                                <div class="non-game-event-title"></div>
                                <div class="special-event">
                                    <div class="icon-wrapper" title="Special Event">
                                        <i class="fas fa-asterisk"></i>
                                    </div>
                                    <div class="label" title="Special Event">Special Event</div>
                                </div>
                                
                                <?php 
                                }
                                ?>
                                <!--special event ends-->
                                
                            </td>
                            <!--match up -td ends-->
                            <!--mega column-->
                            <td class="megacolumn-td-small">
                                
                                <?php
                                //echo 'is this working?';
                                if(!$popup_appear){//past game // AWAY
                                    ?>
                                    <!--<a class="score" href="">
                                        <span class="outcome">
                                            <?php 
                                                $output;
                                                /* if($result2withpost[$day][0]=='win')
                                                    $output ="W";
                                                if($result2withpost[$day][0]=='loss')
                                                    $output ='L';  
                                                if($result2withpost[$day][0]=='tie')
                                                    $output ='T';     */  
												$output= $game2withpost.getResult();
                                                echo $output.', ';
                                            ?>
                                            <?php 
                                                //away_score
                                                echo $away_score;
                                                echo ' - ';
                                                echo $home_score;
                                            ?>
                                        </span>
                                        <span class="left-score-tricode"></span>
                                        <span class="left-score"></span>
                                        <span class="hyphen"></span>
                                        <span class="right-score-tricode"></span>
                                        <span class="right-score"></span>
                                    </a>-->
                                    <!--a for score ended-->

                                <?php
                                }//if(!$popup_appear) ends
                                else{
                                ?>
                                    <a href="/" class="time">
                                        <div class="primary-time">
                                            <?php 
                                                echo $time2withpost[$day];
                                            ?>
                                        </div>
                                    </a>                                
                                <?php 
                                }
                                ?>
                            </td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="short-view-blank-td"><div></div></td>
                            <td class="tickets-arrows-wrap-td-small"></td>
                            <?php 
                                }//AWAY ENDS 
                            ?>
                        </tr>   
                    </tbody>
                </table>
            </div><!--list-mode-table-wrapper" id="MOBILE" ENDS-->
            <?php
            }
            ?>
       <!--</div><!--list-mode ENDS-->
<!--</section>MOBILE SECTION END-->  
        <?php  
          if(count($teamwithpost[$day])<=2){
            //desktop
          $calendar_output .= '<a data-tooltip data-options="disable_for_touch:true" class="has-tip" href="' . 
        ( sizeof( $daywithpost[ $day ] ) > 1 ? add_query_arg( array( 'post_type' => 'sp_event' ), get_day_link( $thisyear, $thismonth, $day ) ) . 
        '" title="' . sprintf( __( '%s events', 'sportspress' ),
        ( sizeof( $daywithpost[ $day ] ) ) ) : 
            get_post_permalink( $daywithpost[ $day ][0], false, true ) . 
            '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) ) .
            "\" itemprop=\"url\">";
          $calendar_output.=  '<div class="team_thumbnail">'.get_the_post_thumbnail(  $teamwithpost[$day][0], 'calendar-icon' );
          $calendar_output.=  'vs';
         
          $calendar_output.=  get_the_post_thumbnail(  $teamwithpost[$day][1], 'calendar-icon' ).'</div>';
          $calendar_output.= '<div class="calendar_event_time">'. $timewithpost[$day].'</div>';
          //desktop end
          //mobile start
          ?>

 


          <?php
          if($gamewithpost[$day]->special_event=="yes"){
             
              $calendar_output.='<section class="day_icon"><i class="fas fa-asterisk"></i></section>';
          }
          if($gamewithpost[$day]->home_away=="home" && $popup_appear){
             
              $calendar_output.='<section class="day_icon"><i class="fas fa-ticket-alt fa-ticket-desktop"></i></section>';
          }
          $calendar_output.='</div></a></div>';
          }//count<=2 ends
          else{
            
         
          $calendar_output.=  '<div class="team_thumbnail">'.get_the_post_thumbnail(  $teamwithpost[$day][0], 'calendar-icon' );
          $calendar_output.=  'vs';
          $calendar_output.=  get_the_post_thumbnail(  $teamwithpost[$day][1], 'calendar-icon' ).'</div>';
          $calendar_output.= '<div class="calendar_event_time">'.$timewithpost[$day].'</div>';  
          
          $calendar_output .= '<a data-tooltip data-options="disable_for_touch:true" class="has-tip" href="'. get_post_permalink( $day2withpost[ $day ][0], false, true ).'".title="' . esc_attr( $ak_titles_for_day[ $day ] )  .
            "\" itemprop=\"url\">";

          $calendar_output.=  '<div class="team_thumbnail">'.get_the_post_thumbnail(  $teamwithpost[$day][2], 'calendar-icon' );
          $calendar_output.=  'vs';
          $calendar_output.=  get_the_post_thumbnail(  $teamwithpost[$day][3], 'calendar-icon' ).'</div>';
          $calendar_output.= '<div class="calendar_event_time">'.$time2withpost[$day].'</div></a>';  
          
  
          if($specialeventwithpost[$day][0]=="yes" || $specialevent2withpost[$day][0]=="yes"){ // have a special event 
             
              $calendar_output.='<section class="day_icon"><i class="fas fa-asterisk"></i></section>';
            }
          if($homeawaywithpost[$day][0]=="home" || $homeaway2withpost[$day][0]=="home"){
                if($popup_appear){

                $calendar_output.='<section class="day_icon"><i class="fas fa-ticket-alt fa-ticket-desktop"></i></section>';
                }
          }
            $calendar_output.='</div></a></div>';
    }


    }//end if($day_has_posts)
    else
        $calendar_output .= '<div class="td_date">'.$day.'</div>';
        $calendar_output .= '</td>';

    if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) ){
        $newrow = true;
        $firstdayofweek = $day;
        $rowcount+=1;
    }
}

$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
if ( $pad != 0 && $pad != 7 )
    $calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>\n\t</div>";

if ( $id && $show_all_events_link )
    $calendar_output .= '<div class="ss-calendar-link ss-view-all-link"><a href="' . get_permalink( $id ) . '">' . __( 'View all events', 'sportspress' ) . '</a></div>';
?>
</div><!--list-mode ends-->
</section><!--mobile section ends-->
<div class="ss-template ss-template-event-calendar" id="DESKTOP">
    <?php echo $calendar_output; ?>
</div>




<div class="event_calendar_icons">
    <div class="grid-only-icons">
        <div class="symbol-label-pair-home desktop-icons">
            <div class="symbol">
                <div class="color-block-home"><i class="fas fa-square" id="home_icon"></i></div>
            </div>
            <div class="icon-label"> - Home</div>
        </div>
        <div class="symbol-label-pair-away desktop-icons">
            <div class="symbol">
                <div class="color-block-away"><i class="fas fa-square" id="away_icon"></i></div>
            </div>
            <div class="icon-label"> - Away</div>
        </div>

        <div class="symbol-label-pair-special">
            <div class="symbol">
                <div class="color-block-special-event"><i class="fas fa-asterisk"></i></div>
            </div>
            <div class="icon-label"> - Special Event</div>
        </div>
        <div class="symbol-label-pair-ticket">
            <div class="symbol">
                <div class="color-block-ticket"><i class="fas fa-ticket-alt fa-ticket-desktop"></i></div>
            </div>
            <div class="icon-label"> - Buy Tickets</div>
        </div>
    </div>
</div> <!--EVENT CALENDAR ICONS ENDS-->


<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>

<?php
class Game{
	public $post_id;
    public $home_away;
    public $game_time;
    public $special_event;
    public $event_description;
    public $opponent;
    public $stallions_team;
    public $stallions_result;
    public $home_score;
    public $away_score;
	
	public function get_result(){
		if(isset($home_away) && isset($away_score) && isset($home_score)){
			if($home_away == "home"){
				if ($home_score>$away_score)
					return "W";
				else
					return "L";
			}
			if($home_away == "away"){
				if ($home_score<$away_score)
					return "W";
				else
					return "L";
			}
			return "E";
		}
		return "E2";
	}
}

?>

