<?php
/*
Template Name: buy_individual_ticket
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
$myweek = array();

for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
    $myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
}

foreach ( $myweek as $wd ) {
    $day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
    $wd = esc_attr($wd);
}

?>

<!-- MOBILE WEBSITE-->
<section>
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
    $hometeam = 0;
    foreach ( (array) $dayswithposts as $daywith ) {
        $teams = (array) get_post_meta( $daywith[1], 'sp_team' );
        $home_away = get_post_meta($daywith[1], 'home_away');
        $time = get_the_time( get_option('time_format'), $daywith[1] );
        $special_event = get_post_meta($daywith[1], '_special_event');
        $event_description = get_post_meta($daywith[1], '_event_description');
        $opponent=0;
        if($hometeam==0 && get_the_title($teams[0], 'sp_team')=='Saugerties Stallions'){
            $hometeam=$teams[0];
            //echo $hometeam;
            $opponent=$teams[1];
        }
        if($hometeam==0 && get_the_title($teams[1], 'sp_team')=='Saugerties Stallions'){
            $hometeam=$teams[1];
            //echo $hometeam;
            $opponent=$teams[0];
        }

        $result = (array)get_post_meta($daywith[1], 'sp_results');
        //echo var_dump($result);
        $stallions_result= (($result[0])[$hometeam]['outcome'][0]);
        $games_score_1=(($result[0])[$hometeam]['r']);
        $games_score_2=(($result[0])[$opponent]['r']);
        //echo $games_score_1;
        //echo $games_score_2;

    if( count( $daywithpost[$daywith[0]])!=0){
        $day2withpost[ $daywith[0] ][] = $daywith[1];
        $time2withpost [$daywith[0]]=$time;
        $teamwithpost[$daywith[0]][2] = $teams[0];
        $teamwithpost[$daywith[0]][3] = $teams[1];
        $homeaway2withpost[$daywith[0]]=$home_away;
        $specialevent2withpost[$daywith[0]]=$special_event;
        $specialeventdes2withpost[$daywith[0]]=$event_description ;
        $result2withpost[$daywith[0]][0]=$stallions_result;
        $result2withpost[$daywith[0]][1]=$games_score_1;
        $result2withpost[$daywith[0]][2]=$games_score_2;
        $opponent2withpost[$daywith[0]]=$opponent;
    }

    if(count( $daywithpost[$daywith[0]])==0){

        $daywithpost[ $daywith[0] ][] = $daywith[1];
        $timewithpost[$daywith[0]]=$time;
        $teamwithpost[$daywith[0]][0] = $teams[0];
        $teamwithpost[$daywith[0]][1] = $teams[1];
        $homeawaywithpost[$daywith[0]]=$home_away;
        $specialeventwithpost[$daywith[0]]=$special_event;
        $specialeventdeswithpost[$daywith[0]]=$event_description ;
        $resultwithpost[$daywith[0]][0]=$stallions_result;
        $resultwithpost[$daywith[0]][1]=$games_score_1;
        $resultwithpost[$daywith[0]][2]=$games_score_2;
        $opponentwithpost[$daywith[0]]=$opponent;
    }

        //echo '<pre>';
        //var_dump($homeawaywithpost);
        //echo '</pre>';
    }
} else {
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


$daysinmonth = intval(date('t', $unixmonth));
$rowcount= 0;
$lastrow = false;
$firstdayofweek = 0;
?>
<div class="ticket-list-mode">
<?php
$_odd = true; // this is for the mobile schedule page alternating backgroundcolor
for ( $day = 1; $day <= $daysinmonth; ++$day ) {
    if ( isset($newrow) && $newrow ){
        ?>
        <!--border in starting new week-->
<?php
    }
    $newrow = false;
    if($daysinmonth-$firstdayofweek<=6){
        $lastrow=true;
    }
    $day_has_posts = array_key_exists($day, $daywithpost);
    if ( $day_has_posts ) {// any posts today?
          ?>

<?php
       $popup_appear= false;
       if($thisyear == gmdate('Y', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp'))
       && $day >= gmdate('j', current_time('timestamp'))){
           $popup_appear=true;
       }
       if($thisyear >= gmdate('Y', current_time('timestamp')) && $thismonth > gmdate('m', current_time('timestamp'))){
           $popup_appear=true;
       }
       
       /**else{
           $popup_appear=false;
       }
       **/
			 if($popup_appear==true && $homeawaywithpost[$day][0]=="home"){
        ?>
           <div class="ticket-list-mode-table-wrapper">
                <?php
                    if($_odd){
                 ?>
                 <table class="ticket-list-mode-game table-odd">
                 <?php
                 $_odd=false;
                }//odd
                    else{
                ?>
                 <table class="ticket-list-mode-game table-even">

                <?php
                $_odd=true;
                }
                ?>
                    <tbody>
                        <tr class="ticket-primary-row-tr">
                            <td class="ticket-date-td">
                                <div class="ticket-month-date">
                                    <!-- format: Mar 19-->
                                    <?php
                                          //                                        echo date('M', strtotime($thismonth . '01'));
                                        //echo $day;

                                        $unixTimestamp = strtotime($thisyear.'-'.$thismonth.'-'.$day);

                                        echo date('M', $unixTimestamp);
                                        echo $day;
                                    ?>
                                </div>
                                <div class="ticket-weekday">
                                    <?php
                                         $unixTimestamp = strtotime($thisyear.'-'.$thismonth.'-'.$day);

                                         $nameOfDay = date('D', $unixTimestamp);
                                         echo $nameOfDay;
                                         //$weekday = date("l", $unixTimestamp);
                                         //echo $weekday;
                                    ?>
                                </div>
                            </td>
                            <?php
                            if($homeawaywithpost[$day][0]=="home"){
                            ?>
                            <td class="ticket-matchup-td">
                                <div class="ticket-matchup">
                                    <div class="ticket-xref-home-team-wrapper">
                                        <div class="ticket-xref-home-logo-wrapper">

                                        </div>
                                    </div>
                                    <!--xref-away-team-wrapper ends-->
                                    <!-- at-or-us -->
                                    <div class="ticket-at-or-us">
                                        <div class="ticket-ss-against-note"></div>
                                        <div class="ticket-vs">vs.</div>
                                    </div>
                                    <!-- at-or-us ends-->
                                    <!--opponent-logo starts-->
                                    <div class="ticket-opponent-logo-wrapper">
                                     <?php
                                        if(get_the_title($teamwithpost[$day][0])=="Saugerties Stallions"){
                                            $opponent_name=get_the_title($teamwithpost[$day][1]);
                                             $url=get_the_post_thumbnail_url(  $teamwithpost[$day][1]);
                                             $away_score=$resultwithpost[$day][1];
                                             $home_score=$resultwithpost[$day][0];
                                             // $url=get_the_post_thumbnail_url(  $teamwithpost[$day][1], 'calendar-icon');
                                             ?>
                                             <img src="<?php echo $url; ?>" class="ticket-opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                        else{
                                            $opponent_name=get_the_title($teamwithpost[$day][0]);
                                            $url= get_the_post_thumbnail_url($teamwithpost[$day][0]);
                                            $away_score=$resultwithpost[$day][0];
                                            $home_score=$resultwithpost[$day][1];
                                            ?>
                                             <img src="<?php echo $url; ?>" class="ticket-opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                    ?>
                                    </div>
                                    <div class="ticket-ticket-opponent-name"><?php echo $opponent_name ?></div>
                                    <a class="non-game-event-logo-link" href style="display:none;">
                                        <img class="non-game-event-logo">
                                    </a>
                                </div>
                                <?php
                                if($specialeventwithpost[$day][0]=="yes"){//FUTURE game displays special events
                                    ?>

                                <div class="ticket-non-game-event-title"></div>
                                <div class="ticket-special-event">
                                    <div class="ticket-icon-wrapper" title="Special Event">
                                        <i class="fas fa-asterisk fa-sm"></i>
                                    </div>
                                    <div class="ticket-label" title="Special Event">Special Event</div>
                                </div>

                                <?php
                                }
                                ?>
                            </td>
                            <td class="ticket-megacolumn-td-small">
              											<a href="/" class="time">
                                        <div class="ticket-primary-time">
                                            <?php
                                                echo $timewithpost[$day];
                                            ?>
                                        </div>
                                    </a>
                                <?php
                                }//future games end
                                ?>
                            <td class="ticket-short-view-blank-td"><div></div></td>
                            <td class="ticket-short-view-blank-td"><div></div></td>
                            <td class="ticket-short-view-blank-td"><div></div></td>
                            <td class="ticket-short-view-blank-td"><div></div></td>
                            <td class="ticket-tickets-arrows-wrap-td-small">
                                    <?php 
                                    $calendar_output .= '<a class="small-ticket-link icon-wrapper tickets" class="has-tip" href="' . 
                                    ( sizeof( $daywithpost[ $day ] ) > 1 ? add_query_arg( array( 'post_type' => 'sp_event' ), get_day_link( $thisyear, $thismonth, $day ) ) . 
                                    '" title="' . sprintf( __( '%s events', 'sportspress' ),
                                    ( sizeof( $daywithpost[ $day ] ) ) ) : 
                                        get_post_permalink( $daywithpost[ $day ][0], false, true ) . 
                                        '."#ticket_section"." title="' . esc_attr( $ak_titles_for_day[ $day ] ) ) .
                                        "\" itemprop=\"url\">";
                                        echo $calendar_output;
                                    ?>
                                    <!-- <a class="small-ticket-link icon-wrapper tickets"
                                    href="/"
                                    target="_blank">-->
                                        <!--<i class="fas fa-ticket-alt fa-ticket-mobile"></i>-->
                                        <button class="buy_ticket_button" type="button">Buy Ticket</button>
                                    </a>
                            </td>
                            </td>
                            <!--HOME ENDS-->
                        </tr>
                    </tbody>
                </table>
            </div><!--list-mode-table-wrapper" id="MOBILE" ENDS-->
            <?php
            if(count($teamwithpost[$day])>2){ //if there are more than one game at that day, add one more div block for that game?>
               <div class="ticket-list-mode-table-wrapper" id="MOBILE">
                <?php
                    if($_odd){
                 ?>
                 <table class="ticket-list-mode-game table-odd">
                 <?php
                 $_odd=false;
                }//odd
                else{
                ?>
                 <table class="ticket-list-mode-game table-even">
                <?php
                $_odd=true;
                }
                ?>
                    <tbody>
                        <tr class="ticket-primary-row-tr">
                            <td class="ticket-date-td">
                                <div class="ticket-month-date">
                                    <!-- format: Mar 19-->
                                    <?php
                                        $unixTimestamp = strtotime($thisyear.'-'.$thismonth.'-'.$day);

                                        echo date('M', $unixTimestamp);
                                        echo $day;
                                    ?>
                                </div>
                                <div class="ticket-weekday">
                                    <?php
                                         $unixTimestamp = strtotime($thisyear.'-'.$thismonth.'-'.$day);

                                         $nameOfDay = date('D', $unixTimestamp);
                                         echo $nameOfDay;
                                         //$weekday = date("l", $unixTimestamp);
                                         //echo $weekday;
                                    ?>
                                </div>
                            </td>
                            <?php
                            if($homeaway2withpost[$day][0]=="home"){
                            ?>
                            <td class="ticket-matchup-td">
                                <div class="ticket-matchup">
                                    <div class="ticket-xref-home-team-wrapper">
                                        <div class="ticket-xref-home-logo-wrapper">

                                        </div>
                                    </div>
                                    <!--xref-away-team-wrapper ends-->
                                    <!-- at-or-us -->
                                    <div class="ticket-at-or-us">
                                        <div class="ticket-ss-against-note"></div>
                                        <div class="ticket-vs">vs.</div>
                                    </div>
                                    <!-- at-or-us ends-->
                                    <!--opponent-logo starts-->
                                    <div class="ticket-opponent-logo-wrapper">
                                     <?php
                                        if(get_the_title($teamwithpost[$day][2])=="Saugerties Stallions"){
                                            $opponent_name=get_the_title($teamwithpost[$day][3]);
                                             $url=get_the_post_thumbnail_url(  $teamwithpost[$day][3]);
                                             $away_score=$result2withpost[$day][1];
                                             $home_score=$result2withpost[$day][0];
                                             // $url=get_the_post_thumbnail_url(  $teamwithpost[$day][1], 'calendar-icon');
                                             ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                        else{
                                            $opponent_name=get_the_title($teamwithpost[$day][2]);
                                            $url= get_the_post_thumbnail_url($teamwithpost[$day][2]);
                                            $away_score=$result2withpost[$day][0];
                                            $home_score=$result2withpost[$day][1];
                                            ?>
                                             <img src="<?php echo $url; ?>" class="opponent-logo-img" style="height:18px; width:18px;">
                                             <?php
                                        }
                                    ?>
                                    </div>
                                    <!--opponent-logo ends-->
                                    <div class="ticket-opponent-name"><?php echo $opponent_name ?></div>
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

                                <div class="ticket-non-game-event-title"></div>
                                <div class="ticket-special-event">
                                    <div class="ticket-icon-wrapper" title="Special Event">
                                        <i class="fas fa-asterisk fa-sm"></i>
                                    </div>
                                    <div class="ticket-label" title="Special Event">Special Event</div>
                                </div>

                                <?php
                                }
                                ?>
                                <!--special event ends-->
                            </td>
                            <!--match up -td ends-->
                            <!--mega column-->
                            <td class="ticket-megacolumn-td-small">
          							<a href="/" class="time">
                                        <div class="ticket-primary-time">
                                            <?php
                                                echo $time2withpost[$day];
                                            ?>
                                        </div>
                                    </a>
                            <td class="ticket-short-view-blank-td"><div></div></td>
                            <td class="ticket-short-view-blank-td"><div></div></td>
                            <td class="ticket-short-view-blank-td"><div></div></td>
                            <td class="ticket-short-view-blank-td"><div></div></td>
                            <td class="ticket-tickets-arrows-wrap-td-small">
                                <a class="small-ticket-link icon-wrapper tickets"
                                    href="/"
                                    target="_blank">
                                    <i class="fas fa-ticket-alt fa-ticket-mobile"></i>
                                </a>
                            </td>
                            </td>
                            <!--HOME ENDS-->
                            <?php 
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div><!--list-mode-table-wrapper" id="MOBILE" ENDS-->
            <?php
            }
			 }
        }
           } ?>
       <!--</div><!--list-mode ENDS-->
<!--</section>MOBILE SECTION END-->
</div><!--list-mode ends-->
</section><!--mobile section ends-->

<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>
