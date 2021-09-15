<?php
/*
Template Name: Roster_mobile_friend
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
<?php //echo "roster template page";?>


    <?php the_content(); ?>
    <div class="teamw" id="DESKTOP_ROSTER">
    <h4><?php echo date("Y"); ?> Saugerties Stallions Roster »</h4>
    <div class="rosterpagebody" id="DESKTOP_ROSTER">
        <table class="rostertable" id="DESKTOP_ROSTER">
        <tbody class="rosterbody" id="DESKTOP_ROSTER">
    <section class="roster-pitcher">

        <!--PITCHER TABLE-->
        <tr class="rosterpos">
        <td colspan="9">Pitchers »</td>
        </tr>
        <!--Pitcher Players-->
        <tr class="rosterstats">
        <td></td>
        <td>NAME</td>
        <td>#</td>
        <td>BATS</td>
        <td>THROWS</td>
        <td>HEIGHT</td>
        <td>WEIGHT</td>
        <td>RESIDES IN</td>
        <td>COLLEGE</td>
        </tr>
        <?php

         $args = array(
            'post_type'  => 'player',
            'posts_per_page' => -1, //for all posts
            'post_status' => 'publish',
            'orderby' => '_player_lastname',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => "_player_position",
                    'value' => "Pitcher",
                )
            ),
        );
        // The Query
        $the_pitcher_query = new WP_Query($args);
        // The Loop
        if ( $the_pitcher_query->have_posts() ) { ?>

            <?php
            while ( $the_pitcher_query->have_posts() ) {
                $the_pitcher_query->the_post();
                $player_firstname=get_post_meta(get_the_ID(), '_player_firstname', true);
                $player_lastname=get_post_meta(get_the_ID(), '_player_lastname', true);
                $player_name=$player_firstname.' '.$player_lastname;
                $player_number=get_post_meta(get_the_ID(), '_player_number', true);
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="left"){
                    $player_bats="L";
                }
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="right"){
                    $player_bats="R";
                }
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="switch"){
                    $player_bats="S";
                }
                if(get_post_meta(get_the_ID(), '_player_throws', true)=="left"){
                    $player_throws="L";
                }
                if(get_post_meta(get_the_ID(), '_player_throws', true)=="right"){
                    $player_throws="R";
                }
                $player_height=get_post_meta(get_the_ID(), '_player_height', true);
                $player_weight=get_post_meta(get_the_ID(), '_player_weight', true);
                $player_weight.=' lbs';
                $player_resides=get_post_meta(get_the_ID(), '_player_resides', true);
                $player_college=get_post_meta(get_the_ID(), '_player_college', true);
                $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));

                $link=get_permalink();

            ?>
            <tr class="rosterplayer">
                <td><img class="rosterimg" src=<?php echo $url;?>></td>
                <td><a href=<?php echo $link; ?>>
                    <?php echo $player_name?></a>
                </td>
                <td><?php echo $player_number;?></td>
                <td><?php echo $player_bats;?></td>
                <td><?php echo $player_throws;?></td>
                <td><?php echo $player_height;?></td>
                <td><?php echo $player_weight;?></td>
                <td><?php echo $player_resides;?></td>
                <td><?php echo $player_college;?></td>
            </tr>
            <?php
            }
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_query();
        wp_reset_postdata();
        ?>
    </section>

    <!--PITCHER ENDS, CATCHER STARTS-->
    <section class="roster-catcher" id="DESKTOP_ROSTER">
        <!--Catcher TABLE-->
        <tr class="rosterpos">
        <td colspan="9">Catchers »</td>
        </tr>
        <!--Pitcher Players-->
        <tr class="rosterstats">
        <td></td>
        <td>NAME</td>
        <td>#</td>
        <td>BATS</td>
        <td>THROWS</td>
        <td>HEIGHT</td>
        <td>WEIGHT</td>
        <td>RESIDES IN</td>
        <td>COLLEGE</td>
        </tr>
        <?php

         $args = array(
            'post_type'  => 'player',
            'posts_per_page' => -1, //for all posts
            'post_status' => 'publish',
            'orderby' => '_player_lastname',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => "_player_position",
                    'value' => "Catcher",
                )
            ),
        );
        // The Query
        $the_pitcher_query = new WP_Query($args);
        // The Loop
        if ( $the_pitcher_query->have_posts() ) { ?>

            <?php
            while ( $the_pitcher_query->have_posts() ) {
                $the_pitcher_query->the_post();
                $player_firstname=get_post_meta(get_the_ID(), '_player_firstname', true);
                $player_lastname=get_post_meta(get_the_ID(), '_player_lastname', true);
                $player_name=$player_firstname.' '.$player_lastname;
                $player_number=get_post_meta(get_the_ID(), '_player_number', true);
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="left"){
                    $player_bats="L";
                }
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="right"){
                    $player_bats="R";
                }
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="switch"){
                    $player_bats="S";
                }
                if(get_post_meta(get_the_ID(), '_player_throws', true)=="left"){
                    $player_throws="L";
                }
                if(get_post_meta(get_the_ID(), '_player_throws', true)=="right"){
                    $player_throws="R";
                }
                $player_height=get_post_meta(get_the_ID(), '_player_height', true);
                $player_weight=get_post_meta(get_the_ID(), '_player_weight', true);
                $player_weight.=' lbs';
                $player_resides=get_post_meta(get_the_ID(), '_player_resides', true);
                $player_college=get_post_meta(get_the_ID(), '_player_college', true);
                $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                $link=get_permalink();
            ?>
            <tr class="rosterplayer">
                <td><img class="rosterimg" src=<?php echo $url;?>></td>
                <td><a href=<?php echo $link; ?>>
                    <?php echo $player_name;?></a>
                </td>
                <td><?php echo $player_number;?></td>
                <td><?php echo $player_bats;?></td>
                <td><?php echo $player_throws;?></td>
                <td><?php echo $player_height;?></td>
                <td><?php echo $player_weight;?></td>
                <td><?php echo $player_resides;?></td>
                <td><?php echo $player_college;?></td>
            </tr>
            <?php
            }
            //echo '</ul>';
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_query();
        wp_reset_postdata();
        ?>
    </section>

    
    <!--Catcher ends, Infielder starts-->
     <section class="roster-infielder" id="DESKTOP_ROSTER">
        <!--Infielder TABLE-->
        <tr class="rosterpos">
        <td colspan="9">Infielders »</td>
        </tr>
        <!--Infielder Players-->
        <tr class="rosterstats">
        <td></td>
        <td>NAME</td>
        <td>#</td>
        <td>BATS</td>
        <td>THROWS</td>
        <td>HEIGHT</td>
        <td>WEIGHT</td>
        <td>RESIDES IN</td>
        <td>COLLEGE</td>
        </tr>
        <?php

         $args = array(
            'post_type'  => 'player',
            'posts_per_page' => -1, //for all posts
            'post_status' => 'publish',
            'orderby' => '_player_lastname',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => "_player_position",
                    'value' => "Infielder",
                )
            ),
        );
        // The Query
        $the_infielder_query = new WP_Query($args);
        // The Loop
        if ( $the_infielder_query->have_posts() ) { ?>

            <?php
            while ( $the_infielder_query->have_posts() ) {
                $the_infielder_query->the_post();
                $player_firstname=get_post_meta(get_the_ID(), '_player_firstname', true);
                $player_lastname=get_post_meta(get_the_ID(), '_player_lastname', true);
                $player_name=$player_firstname.' '.$player_lastname;
                $player_number=get_post_meta(get_the_ID(), '_player_number', true);
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="left"){
                    $player_bats="L";
                }
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="right"){
                    $player_bats="R";
                }
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="switch"){
                    $player_bats="S";
                }
                if(get_post_meta(get_the_ID(), '_player_throws', true)=="left"){
                    $player_throws="L";
                }
                if(get_post_meta(get_the_ID(), '_player_throws', true)=="right"){
                    $player_throws="R";
                }
                $player_height=get_post_meta(get_the_ID(), '_player_height', true);
                $player_weight=get_post_meta(get_the_ID(), '_player_weight', true);
                $player_weight.=' lbs';
                $player_resides=get_post_meta(get_the_ID(), '_player_resides', true);
                $player_college=get_post_meta(get_the_ID(), '_player_college', true);
                $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                $link=get_permalink();
            ?>
            <tr class="rosterplayer">
                <td><img class="rosterimg" src=<?php echo $url;?>></td>
                <td><a href=<?php echo $link; ?>>
                    <?php echo $player_name;?></a>
                </td>
                <td><?php echo $player_number;?></td>
                <td><?php echo $player_bats;?></td>
                <td><?php echo $player_throws;?></td>
                <td><?php echo $player_height;?></td>
                <td><?php echo $player_weight;?></td>
                <td><?php echo $player_resides;?></td>
                <td><?php echo $player_college;?></td>
            </tr>
            <?php
            }
            //echo '</ul>';
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_query();
        wp_reset_postdata();
        ?>
    </section>

    <!--OUTFIELDER starts-->
     <section class="roster-outfielder" id="DESKTOP_ROSTER">
        <!--OUTFIELDER  TABLE-->
        <tr class="rosterpos">
        <td colspan="9">Outfielders »</td>
        </tr>
        <!--OUTFIELDER  Players-->
        <tr class="rosterstats">
        <td></td>
        <td>NAME</td>
        <td>#</td>
        <td>BATS</td>
        <td>THROWS</td>
        <td>HEIGHT</td>
        <td>WEIGHT</td>
        <td>RESIDES IN</td>
        <td>COLLEGE</td>
        </tr>
        <?php

         $args = array(
            'post_type'  => 'player',
            'posts_per_page' => -1, //for all posts
            'post_status' => 'publish',
            'orderby' => '_player_lastname',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => "_player_position",
                    'value' => "Outfielder",
                )
            ),
        );
        // The Query
        $the_outfielder_query = new WP_Query($args);
        // The Loop
        if ( $the_outfielder_query->have_posts() ) { ?>

            <?php
            while ( $the_outfielder_query->have_posts() ) {
                $the_outfielder_query->the_post();
                $player_firstname=get_post_meta(get_the_ID(), '_player_firstname', true);
                $player_lastname=get_post_meta(get_the_ID(), '_player_lastname', true);
                $player_name=$player_firstname.' '.$player_lastname;
                $player_number=get_post_meta(get_the_ID(), '_player_number', true);
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="left"){
                    $player_bats="L";
                }
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="right"){
                    $player_bats="R";
                }
                if(get_post_meta(get_the_ID(), '_player_bats', true)=="switch"){
                    $player_bats="S";
                }
                if(get_post_meta(get_the_ID(), '_player_throws', true)=="left"){
                    $player_throws="L";
                }
                if(get_post_meta(get_the_ID(), '_player_throws', true)=="right"){
                    $player_throws="R";
                }
                $player_height=get_post_meta(get_the_ID(), '_player_height', true);
                $player_weight=get_post_meta(get_the_ID(), '_player_weight', true);
                $player_weight.=' lbs';
                $player_resides=get_post_meta(get_the_ID(), '_player_resides', true);
                $player_college=get_post_meta(get_the_ID(), '_player_college', true);
                $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                $link=get_permalink();
            ?>
            <tr class="rosterplayer">
                <td><img class="rosterimg" src=<?php echo $url;?>></td>
                <td><a href=<?php echo $link; ?>>
                    <?php echo $player_name;?></a>
                </td>
                <td><?php echo $player_number;?></td>
                <td><?php echo $player_bats;?></td>
                <td><?php echo $player_throws;?></td>
                <td><?php echo $player_height;?></td>
                <td><?php echo $player_weight;?></td>
                <td><?php echo $player_resides;?></td>
                <td><?php echo $player_college;?></td>
            </tr>
            <?php
            }
            //echo '</ul>';
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_query();
        wp_reset_postdata();
        ?>
    </section>

        </tbody>
        </table>
    </div>
    </div>


    <!--mobile page-->
    <div class="mobile_teamw" id="MOBILE_ROSTER">
    <h4><?php echo date("Y"); ?> Saugerties Stallions Roster »</h4>
    <div class="mobile_rosterpagebody" id="MOBILE_ROSTER">
        <table class="mobile_rostertable" id="MOBILE_ROSTER">
        <tbody class="mobile_rosterbody" id="MOBILE_ROSTER">
          <section class="mobile_roster-pitcher" id="MOBILE_ROSTER">
      
              <!--PITCHER TABLE-->
              <tr class="mobile_rosterpos">
              <td colspan="5">Pitchers »</td>
              </tr>
              <!--Pitcher Players-->
              <tr class="mobile_rosterstats">
              <td></td>
              <td>NAME</td>
              <td>#</td>
              <td>B/T</td>
              </tr>
              <?php
      
               $args = array(
                  'post_type'  => 'player',
                  'posts_per_page' => -1, //for all posts
                  'post_status' => 'publish',
                  'orderby' => '_player_lastname',
                  'order' => 'ASC',
                  'meta_query' => array(
                      array(
                          'key' => "_player_position",
                          'value' => "Pitcher",
                      )
                  ),
              );
              // The Query
              $the_pitcher_query = new WP_Query($args);
              // The Loop
              if ( $the_pitcher_query->have_posts() ) { ?>
      
                  <?php
                  while ( $the_pitcher_query->have_posts() ) {
                      $the_pitcher_query->the_post();
                      $player_firstname=get_post_meta(get_the_ID(), '_player_firstname', true);
                      $player_lastname=get_post_meta(get_the_ID(), '_player_lastname', true);
                      $player_name=$player_firstname.' '.$player_lastname;
                      $player_number=get_post_meta(get_the_ID(), '_player_number', true);
                      if(get_post_meta(get_the_ID(), '_player_bats', true)=="left"){
                          $player_bats="L";
                      }
                      if(get_post_meta(get_the_ID(), '_player_bats', true)=="right"){
                          $player_bats="R";
                      }
                      if(get_post_meta(get_the_ID(), '_player_bats', true)=="switch"){
                        $player_bats="S";
                    }
                      if(get_post_meta(get_the_ID(), '_player_throws', true)=="left"){
                          $player_throws="L";
                      }
                      if(get_post_meta(get_the_ID(), '_player_throws', true)=="right"){
                          $player_throws="R";
                      }
                      $player_bt=$player_bats.'/'.$player_throws;
                      $player_height=get_post_meta(get_the_ID(), '_player_height', true);
                      $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                      $link=get_permalink();
      
                  ?>
                  <tr class="mobile_rosterplayer">
                      <td><img class="mobile_rosterimg" src=<?php echo $url;?>></td>
                      <td><a href=<?php echo $link; ?>>
                          <?php echo $player_name?></a>
                      </td>
                      <td><?php echo $player_number;?></td>
                      <td><?php echo $player_bt;?></td>
                  </tr>
                  <?php
                  }
              } else {
                  // no posts found
              }
              /* Restore original Post Data */
              wp_reset_query();
              wp_reset_postdata();
              ?>
          </section>
          <section class="roster-catcher" id="MOBILE_ROSTER">
              <!--Catcher TABLE-->
              <tr class="mobile_rosterpos">
              <td colspan="5">Catchers »</td>
              </tr>
              <!--Pitcher Players-->
              <tr class="mobile_rosterstats">
              <td></td>
              <td>NAME</td>
              <td>#</td>
              <td>B/T</td>
              </tr>
              <?php
      
               $args = array(
                  'post_type'  => 'player',
                  'posts_per_page' => -1, //for all posts
                  'post_status' => 'publish',
                  'orderby' => '_player_lastname',
                  'order' => 'ASC',
                  'meta_query' => array(
                      array(
                          'key' => "_player_position",
                          'value' => "Catcher",
                      )
                  ),
              );
              // The Query
              $the_catcher_query = new WP_Query($args);
              // The Loop
              if ( $the_catcher_query->have_posts() ) { ?>
      
                  <?php
                  while ( $the_catcher_query->have_posts() ) {
                      $the_catcher_query->the_post();
                      $player_firstname=get_post_meta(get_the_ID(), '_player_firstname', true);
                      $player_lastname=get_post_meta(get_the_ID(), '_player_lastname', true);
                      $player_name=$player_firstname.' '.$player_lastname;
                      $player_number=get_post_meta(get_the_ID(), '_player_number', true);
                      if(get_post_meta(get_the_ID(), '_player_bats', true)=="left"){
                          $player_bats="L";
                      }
                      if(get_post_meta(get_the_ID(), '_player_bats', true)=="right"){
                          $player_bats="R";
                      }
                      if(get_post_meta(get_the_ID(), '_player_bats', true)=="switch"){
                        $player_bats="S";
                    }
                      if(get_post_meta(get_the_ID(), '_player_throws', true)=="left"){
                          $player_throws="L";
                      }
                      if(get_post_meta(get_the_ID(), '_player_throws', true)=="right"){
                          $player_throws="R";
                      }
                      $player_bt=$player_bats.'/'.$player_throws;
                      $player_height=get_post_meta(get_the_ID(), '_player_height', true);
                      $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                      $link=get_permalink();
                  ?>
                  <tr class="mobile_rosterplayer">
                      <td><img class="mobile_rosterimg" src=<?php echo $url;?>></td>
                      <td><a href=<?php echo $link; ?>>
                          <?php echo $player_name;?></a>
                      </td>
                      <td><?php echo $player_number;?></td>
                      <td><?php echo $player_bt;?></td>
                  </tr>
                  <?php
                  }
                  //echo '</ul>';
              } else {
                  // no posts found
              }
              /* Restore original Post Data */
              wp_reset_query();
              wp_reset_postdata();
              ?>
          </section>
          <section class="roster-infielder" id="MOBILE_ROSTER">
              <!--Catcher TABLE-->
              <tr class="mobile_rosterpos">
              <td colspan="4">Infielders »</td>
              </tr>
              <!--Pitcher Players-->
              <tr class="mobile_rosterstats">
              <td></td>
              <td>NAME</td>
              <td>#</td>
              <td>B/T</td>
              </tr>
              <?php
      
               $args = array(
                  'post_type'  => 'player',
                  'posts_per_page' => -1, //for all posts
                  'post_status' => 'publish',
                  'orderby' => '_player_lastname',
                  'order' => 'ASC',
                  'meta_query' => array(
                      array(
                          'key' => "_player_position",
                          'value' => "Infielder",
                      )
                  ),
              );
              // The Query
              $the_infielder_query = new WP_Query($args);
              // The Loop
              if ( $the_infielder_query->have_posts() ) { ?>
      
                  <?php
                  while ( $the_infielder_query->have_posts() ) {
                      $the_infielder_query->the_post();
                      $player_firstname=get_post_meta(get_the_ID(), '_player_firstname', true);
                      $player_lastname=get_post_meta(get_the_ID(), '_player_lastname', true);
                      $player_name=$player_firstname.' '.$player_lastname;
                      $player_number=get_post_meta(get_the_ID(), '_player_number', true);
                      if(get_post_meta(get_the_ID(), '_player_bats', true)=="left"){
                          $player_bats="L";
                      }
                      if(get_post_meta(get_the_ID(), '_player_bats', true)=="right"){
                          $player_bats="R";
                      }
                      if(get_post_meta(get_the_ID(), '_player_bats', true)=="switch"){
                        $player_bats="S";
                    }
                      if(get_post_meta(get_the_ID(), '_player_throws', true)=="left"){
                          $player_throws="L";
                      }
                      if(get_post_meta(get_the_ID(), '_player_throws', true)=="right"){
                          $player_throws="R";
                      }
                      $player_bt=$player_bats.'/'.$player_throws;
                      $player_height=get_post_meta(get_the_ID(), '_player_height', true);
                      $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                      $link=get_permalink();
                  ?>
                  <tr class="mobile_rosterplayer">
                      <td><img class="mobile_rosterimg" src=<?php echo $url;?>></td>
                      <td><a href=<?php echo $link; ?>>
                          <?php echo $player_name;?></a>
                      </td>
                      <td><?php echo $player_number;?></td>
                      <td><?php echo $player_bt;?></td>
                  </tr>
                  <?php
                  }
                  //echo '</ul>';
              } else {
                  // no posts found
              }
              /* Restore original Post Data */
              wp_reset_query();
              wp_reset_postdata();
              ?>
          </section>
          <section class="roster-outfielder" id="MOBILE_ROSTER">
             <!--OUTFIELDER  TABLE-->
             <tr class="mobile_rosterpos">
             <td colspan="4">Outfielders »</td>
             </tr>
             <!--OUTFIELDER  Players-->
             <tr class="mobile_rosterstats">
             <td></td>
             <td>NAME</td>
             <td>#</td>
             <td>B/T</td>
             </tr>
             <?php
      
              $args = array(
                 'post_type'  => 'player',
                 'posts_per_page' => -1, //for all posts
                 'post_status' => 'publish',
                 'orderby' => '_player_lastname',
                 'order' => 'ASC',
                 'meta_query' => array(
                     array(
                         'key' => "_player_position",
                         'value' => "Outfielder",
                     )
                 ),
             );
             // The Query
             $the_outfielder_query = new WP_Query($args);
             // The Loop
             if ( $the_outfielder_query->have_posts() ) { ?>
      
                 <?php
                 while ( $the_outfielder_query->have_posts() ) {
                     $the_outfielder_query->the_post();
                     $player_firstname=get_post_meta(get_the_ID(), '_player_firstname', true);
                     $player_lastname=get_post_meta(get_the_ID(), '_player_lastname', true);
                     $player_name=$player_firstname.' '.$player_lastname;
                     $player_number=get_post_meta(get_the_ID(), '_player_number', true);
                     if(get_post_meta(get_the_ID(), '_player_bats', true)=="left"){
                         $player_bats="L";
                     }
                     if(get_post_meta(get_the_ID(), '_player_bats', true)=="right"){
                         $player_bats="R";
                     }
                         if(get_post_meta(get_the_ID(), '_player_bats', true)=="switch"){
                        $player_bats="S";
                    }
                     if(get_post_meta(get_the_ID(), '_player_throws', true)=="left"){
                         $player_throws="L";
                     }
                     if(get_post_meta(get_the_ID(), '_player_throws', true)=="right"){
                         $player_throws="R";
                     }
                     $player_bt=$player_bats.'/'.$player_throws;
                     $player_height=get_post_meta(get_the_ID(), '_player_height', true);
                     $url= wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                     $link=get_permalink();
                 ?>
                 <tr class="mobile_rosterplayer">
                     <td><img class="mobile_rosterimg" src=<?php echo $url;?>></td>
                     <td><a href=<?php echo $link; ?>>
                         <?php echo $player_name;?></a>
                     </td>
                     <td><?php echo $player_number;?></td>
                     <td><?php echo $player_bt;?></td>
                 </tr>
                 <?php
                 }
                 //echo '</ul>';
             } else {
                 // no posts found
             }
             /* Restore original Post Data */
             wp_reset_query();
             wp_reset_postdata();
             ?>
         </section>
    </tbody>
    </table>
  </div>
  </div>
</div>


<?php // If Page == Order Then footer-order Else footer ?>
<?php get_footer();?>
<?php // get_footer(); ?>
<?php
