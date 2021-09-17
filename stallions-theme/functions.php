<?php
require get_template_directory() . '/archives-page-functions.php';
//update_option( 'siteurl', 'https://chris.fishcreekrent.com/' );
//update_option( 'home', 'https://chris.fishcreekrent.com/' );


function my_register_sidebars() {
    /* Register the 'primary' sidebar. */
    register_sidebar(
        array(
            'id'            => 'primary',
            'name'          => __( 'Primary Sidebar' ),
            'description'   => __( 'A short description of the sidebar.' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
    /* Repeat register_sidebar() code for additional sidebars. */
    
    register_sidebar( array(
        'name' => 'CountdownWidget',
        'id' => 'countdown-widget',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="rounded">',
        'after_title' => '</h2>',
    ) );
    
    register_sidebar( array(
        'name' => 'UpcomingEvents',
        'id' => 'upcoming-widget',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="rounded">',
        'after_title' => '</h2>',
    ) );

    register_sidebar(array(
        'name' => 'Ad_Section',
        'id' => 'ad', 
        'before_widget' => '<div class = "Ad_Section">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => 'Calendar',
        'id' => 'cal', 
        'before_widget' => '<div class = "Calendar">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    
}
add_action( 'widgets_init', 'my_register_sidebars' );

// add this to functions.php
//register acf fields to Wordpress API
//https://support.advancedcustomfields.com/forums/topic/json-rest-api-and-acf/

function acf_to_rest_api($response, $post, $request) {
    if (!function_exists('get_fields')) return $response;

    if (isset($post)) {
        $acf = get_fields($post->id);
        $response->data['acf'] = $acf;
    }
    return $response;
}
add_filter('rest_prepare_sp_event', 'acf_to_rest_api', 10, 3);

function my_change_status_function( $order_id ) {
    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}
add_action( 'woocommerce_payment_complete', 'my_change_status_function' );

function namespace_theme_stylesheets() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_style( 'all', get_template_directory_uri() . '/fontawesome/css/all.css',false,'1.1','all');
}
add_action( 'wp_enqueue_scripts', 'namespace_theme_stylesheets' );
add_theme_support( 'post-thumbnails' );

/* News Custom Post Type Start */
function post_type_news() {

$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'revisions', // post revisions
'post-formats', // post formats
);

$labels = array(
'name' => _x('News', 'plural'),
'singular_name' => _x('News', 'singular'),
'menu_name' => _x('News', 'admin menu'),
'name_admin_bar' => _x('News', 'admin bar'),
'add_new' => _x('Add New', 'add new'),
'add_new_item' => __('Add New News Article'),
'new_item' => __('New News'),
'edit_item' => __('Edit news'),
'view_item' => __('View news'),
'all_items' => __('All news'),
'search_items' => __('Search news'),
'not_found' => __('No news found.'),
);

$args = array(
'supports' => $supports,
'labels' => $labels,
'public' => true,
'show_in_rest' => true,
'query_var' => true,
'rewrite' => array('slug' => 'news'),
'has_archive' => true,
'hierarchical' => false,
);
register_post_type('News', $args);
}
add_action('init', 'post_type_news');

function news_metaboxes( ) {
   global $wp_meta_boxes;
   add_meta_box('postfunctiondiv', __('Carousel Caption'), 'news_metaboxes_html', 'news', 'normal', 'high');
}
add_action( 'add_meta_boxes_news', 'news_metaboxes' );

function news_metaboxes_html()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $carousel_caption = isset($custom["carousel_caption"][0])?$custom["carousel_caption"][0]:'';
?>
    <label>Carousel Caption:</label><input name="carousel_caption" value="<?php echo $carousel_caption; ?>">
<?php
}
function news_save_post()
{
    if(empty($_POST)) return; //why is prefix_teammembers_save_post triggered by add new? 
    global $post;
    update_post_meta($post->ID, "carousel_caption", $_POST["carousel_caption"]);
}   

add_action( 'save_post_news', 'news_save_post' ); 

/* News Custom Post Type End */

function load_owl_scripts(){
    wp_enqueue_script('owl.carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), '1.0.0', true );
    wp_enqueue_script('settings', get_template_directory_uri() . '/js/settings.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'load_owl_scripts');

function load_owl_styles() {
    wp_register_style('owl-carousel', get_template_directory_uri() .'/css/owl.carousel.min.css', array(), null, 'all' );
    wp_enqueue_style( 'owl-carousel' );
}
add_action( 'wp_enqueue_scripts', 'load_owl_styles' );

function which_template_is_loaded() {
    if ( is_super_admin() ) {
        global $template;
        print_r( $template );
    }
}
//Uncomment this to display where the page is getting the template from 
//add_action( 'wp_footer', 'which_template_is_loaded' );


/*
* sponsor custom post type function
*/
 
function post_type_sponsor() {
$supports = array(
'title', // sponsor name
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'revisions', // post revisions
'post-formats', // post formats
);

  $labels = array(
    'name' => _x('Sponsors', 'plural'),
    'singular_name' => _x('Sponsor', 'singular'),
    'menu_name' => _x('Sponsor', 'admin menu'),
    'name_admin_bar' => _x('Sponsor', 'admin bar'),
    'add_new' => _x('Add New', 'add new'),
    'add_new_item' => __('Add New Sponsor'),
    'new_item' => __('New Sponsor'),
    'edit_item' => __('Edit sponsor'),
    'view_item' => __('View sponsor'),
    'all_items' => __('All sponsors'),
    'search_items' => __('Search sponsor'),
    'not_found' => __('No sponsor found.'),
);
$args = array(
'supports' => $supports,
'labels' => $labels,
'public' => true,
'query_var' => true,
'rewrite' => array('slug' => 'sponsor'),
'has_archive' => true,
'hierarchical' => false,
);
register_post_type('sponsor', $args);
}
add_action('init', 'post_type_sponsor');

function player_post_type() {


  $labels = array(
    'name' => _x('Players', 'plural'),
    'singular_name' => _x('Player', 'singular'),
    'menu_name' => _x('Player', 'admin menu'),
    'name_admin_bar' => _x('Player', 'admin bar'),
    'add_new' => _x('Add New', 'add new'),
    'add_new_item' => __('Add New Player'),
    'new_item' => __('New Player'),
    'edit_item' => __('Edit Player'),
    'view_item' => __('View Player'),
    'all_items' => __('All Players'),
    'search_items' => __('Search Player'),
    'not_found' => __('No team found.'),
);

	
	$args = array(
 'labels' => $labels,
 'has_archive' => true,
 'public' => true,
 'hierarchical' => false,
 'supports' => array(

  
	'title', // team name
	'editor', // post content
	'author', // post author
	'thumbnail', // featured images
	'excerpt', // post excerpt
	'custom-fields', // custom fields
	'revisions', // post revisions
	'post-formats', // post formats
	 'page-attributes'
 ),
 'taxonomies'          => array( 'category' ),
 'rewrite'   => array( 'slug' => 'player' ),
 'show_in_rest' => true,
);
register_post_type('player', $args);
    register_taxonomy( 'categories', array('work'), array(
        'hierarchical' => true, 
        'label' => 'Categories', 
        'singular_label' => 'Category', 
        'rewrite' => array( 'slug' => 'categories', 'with_front'=> false )
        )
    );

    register_taxonomy_for_object_type( 'categories', 'work' ); // Better be safe than sorry
}
add_action('init', 'player_post_type');
?>
<?php
/**
 * Adds a box to the main column on the Post add/edit screens.
 */
function home_away_add_meta_box() {

        add_meta_box(
                'home_away_sectionid', 'home_away', 'home_away_meta_box_callback', 'sp_event'
        ); //you can change the 4th paramter i.e. post to custom post type name, if you want it for something else

}

add_action( 'add_meta_boxes', 'home_away_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function home_away_meta_box_callback( $post ) {
       global $value;
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'home_away_meta_box', 'home_away_meta_box_nonce' );

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $value = get_post_meta( $post->ID, 'home_away', true ); //my_key is a meta_key. Change it to whatever you want

        ?>
        <!--<label for="home_away_new_field"><?php _e( "Choose value:", 'choose_value' ); ?></label>-->
        <br />  
        <?php if(isset($value))
        ?>
        <input type="radio" name="home_away" value="home" <?php checked( $value, 'home' ); ?> >Home<br>
        <input type="radio" name="home_away" value="away" <?php checked( $value, 'away' ); ?> >Away<br>

        <?php

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function home_away_save_meta_box_data( $post_id ) {

        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( !isset( $_POST['home_away_meta_box_nonce'] ) ) {
                return;
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['home_away_meta_box_nonce'], 'home_away_meta_box' ) ) {
                return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }


        // Sanitize user input.
        $new_meta_value = ( isset( $_POST['home_away'] ) ? sanitize_html_class( $_POST['home_away'] ) : '' );

        // Update the meta field in the database.
        update_post_meta( $post_id, 'home_away', $new_meta_value );

}

add_action( 'save_post', 'home_away_save_meta_box_data' );
?>
<?php
/**
 * Adds a box to the main column on the Post add/edit screens.
 */
function special_add_meta_box() {

        add_meta_box(
                'special_sectionid', 'Special Event', 'special_meta_box_callback', 'sp_event'
        ); //you can change the 4th paramter i.e. post to custom post type name, if you want it for something else

}

add_action( 'add_meta_boxes', 'special_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function special_meta_box_callback() {
         global $post;
                 // Add an nonce field so we can check for it later.
        wp_nonce_field( 'special_meta_box', 'special_meta_box_nonce' );

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $special_event = get_post_meta( $post->ID, '_special_event', true ); //my_key is a meta_key. Change it to whatever you want
        $event_description = get_post_meta( $post->ID, '_event_description', true ); //my_key is a meta_key. Change it to whatever you wantz
        ?>

        <?php if(isset($special_event)){
        ?>
        <div class="row">
        <div class="label">Special Event</div>
        <div class="fields">
            <label><input type="radio" name="_special_event" value="yes" <?php if( $special_event  == 'yes') echo 'checked'; ?> /> YES </label>
            <label><input type="radio" name="_special_event" value="no"  <?php if( $special_event  == 'no') echo 'checked'; ?> /> NO </label>
        </div>
    </div>
    <br>
        <?php
        }
            if(isset($event_description)){
            ?>
            <div class="row">
                <div class="label">Special Event Description</div>
                <div class="fields">
                    <textarea  style="width:400px !important; height:250px !important;" name="_event_description"><?php echo $event_description; ?></textarea>
                </div>
            </div>
        <?php
    }
        

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function special_save_meta_box_data( $post_id ) {

        global $post;
        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( !isset( $_POST['special_meta_box_nonce'] ) ) {
                return;
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['special_meta_box_nonce'], 'special_meta_box' ) ) {
                return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }
         
        if(isset($_POST["_special_event"])) :
        update_post_meta($post->ID, '_special_event', $_POST["_special_event"]);
        endif;
         
        if(isset($_POST["_event_description"])) :
        update_post_meta($post->ID, '_event_description', $_POST["_event_description"]);
        endif; 
}

add_action( 'save_post', 'special_save_meta_box_data' );

/**
 * Adds a box to the main column on the Post add/edit screens.
 */
function player_add_meta_box() {

        add_meta_box(
                'player_info_id', 'Player Info', 'player_meta_box_callback', 'player'
        ); //you can change the 4th paramter i.e. post to custom post type name, if you want it for something else

}

add_action( 'add_meta_boxes', 'player_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function player_meta_box_callback() {
         global $post;
                 // Add an nonce field so we can check for it later.
        wp_nonce_field( 'player_meta_box', 'player_meta_box_nonce' );

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $player_position = get_post_meta( $post->ID, '_player_position', true ); 
        $player_firstname = get_post_meta( $post->ID, '_player_firstname', true ); 
        $player_lastname = get_post_meta( $post->ID, '_player_lastname', true );
        $player_number = get_post_meta( $post->ID, '_player_number', true ); 
        $player_bats = get_post_meta( $post->ID, '_player_bats', true ); 
        $player_throws = get_post_meta( $post->ID, '_player_throws', true ); 
        $player_height = get_post_meta( $post->ID, '_player_height', true ); 
        $player_weight = get_post_meta( $post->ID, '_player_weight', true ); 
        $player_resides = get_post_meta( $post->ID, '_player_resides', true ); 
        $player_college = get_post_meta( $post->ID, '_player_college', true ); 
        $player_season_year = get_post_meta( $post->ID, '_player_season_year', true ); 
        ?>

        <?php
        if(isset($player_position)){
        ?>
        <div class="row">
            <div class="label">Player Position</div>
            <div class="fields">
                <label><input type="radio" name="_player_position" value="Pitcher" <?php if($player_position == 'Pitcher') echo 'checked'; ?> /> Pitcher </label>
                <label><input type="radio" name="_player_position" value="Catcher" <?php if($player_position == 'Catcher') echo 'checked'; ?> /> Catcher </label>
                <label><input type="radio" name="_player_position" value="Infielder" <?php if($player_position == 'Infielder') echo 'checked'; ?> /> Infielder </label>
                <label><input type="radio" name="_player_position" value="Outfielder" <?php if($player_position == 'Outfielder') echo 'checked'; ?> /> Outfielder </label>
            </div>
        </div>
        <br> 

        <?php
        }
        if(isset($player_firstname)){
        ?>
        <div class="row">
            <div class="label">Player Firstname</div>
            <div class="fields">
                <input name="_player_firstname" type="text" value="<?php if ($player_firstname) { echo $player_firstname; }?>">
                </input>
            </div>
        </div>
        <br>
        <?php
        }
        if(isset($player_lastname)){
        ?>
        <div class="row">
            <div class="label">Player Lastname</div>
            <div class="fields">
                <input name="_player_lastname" type="text" value="<?php if ($player_lastname) { echo $player_lastname; }?>">
                </input>
            </div>
        </div>
        <br>
        <?php
        }
        if(isset($player_number)){
        ?>
        <div class="row">
            <div class="label">Player Number</div>
            <div class="fields">
                <input name="_player_number" type="text" value="<?php if ($player_number) { echo $player_number; }?>">
                </input>
            </div>
        </div>
        <br>
        <?php
        }
        
        if(isset($player_bats)){
        ?>
        <div class="row">
            <div class="label">Player Bats</div>
            <div class="fields">
                <label><input type="radio" name="_player_bats" value="left" <?php if( $player_bats  == 'left') echo 'checked'; ?> /> Left </label>
                <label><input type="radio" name="_player_bats" value="right"  <?php if( $player_bats  == 'right') echo 'checked'; ?> /> Right </label>
                <label><input type="radio" name="_player_bats" value="switch" <?php if( $player_bats  == 'switch') echo 'checked'; ?> /> Switch </label>
            </div>
        </div>
        <br>   
        <?php
        }
        if(isset($player_throws)){
        ?>
        <div class="row">
            <div class="label">Player Throws</div>
            <div class="fields">
                <label><input type="radio" name="_player_throws" value="left" <?php if( $player_throws  == 'left') echo 'checked'; ?> /> Left </label>
                <label><input type="radio" name="_player_throws" value="right"  <?php if( $player_throws  == 'right') echo 'checked'; ?> /> Right </label>
            </div>
        </div>
        <br>      
        <?php
        }
        if(isset($player_height)){
        ?>
        <div class="row">
            <div class="label">Player Height</div>
            <div class="fields">
                <input name="_player_height" type="text" value="<?php if ($player_height) { echo $player_height; }?>">
                </input>
            </div>
        </div>
        <br>
        <?php
        }
        if(isset($player_weight)){
        ?>
        <div class="row">
            <div class="label">Player Weight</div>
            <div class="fields">
                <input name="_player_weight" type="text" value="<?php if ($player_weight) { echo $player_weight; }?>">
                </input>
            </div>
        </div>
        <br>
        <?php
        }
        if(isset($player_resides)){
        ?>
        <div class="row">
            <div class="label">Player Resides In</div>
            <div class="fields">
                <input name="_player_resides" type="text" value="<?php if ($player_resides) { echo $player_resides; }?>">
                </input>
            </div>
        </div>
        <br>
        <?php
        }
        if(isset($player_college)){
        ?>
        <div class="row">
            <div class="label">Player College</div>
            <div class="fields">
                <input name="_player_college" type="text" value="<?php if ($player_college) { echo $player_college; }?>">
                </input>
            </div>
        </div>
        <br>
<?php
    }
    if(isset($player_season_year)){
     ?>
        <div class="row">
            <div class="label">Player Season Year</div>
            <div class="fields">
                <input name="_player_season_year" type="text" value="<?php if ($player_season_year) { echo $player_season_year; }?>">
                </input>
            </div>
        </div>
        <br>
<?php
    }
}


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function player_save_meta_box_data( $post_id ) {

        global $post;
        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( !isset( $_POST['player_meta_box_nonce'] ) ) {
                return;
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['player_meta_box_nonce'], 'player_meta_box' ) ) {
                return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }
        
        if(isset($_POST["_player_position"])) :
        update_post_meta($post->ID, '_player_position', $_POST["_player_position"]);
        endif;
        
        if(isset($_POST["_player_firstname"])) :
        update_post_meta($post->ID, '_player_firstname', $_POST["_player_firstname"]);
        endif;
         
        if(isset($_POST["_player_lastname"])) :
        update_post_meta($post->ID, '_player_lastname', $_POST["_player_lastname"]);
        endif; 

        if(isset($_POST["_player_number"])) :
        update_post_meta($post->ID, '_player_number', $_POST["_player_number"]);
        endif; 

        if(isset($_POST["_player_bats"])) :
        update_post_meta($post->ID, '_player_bats', $_POST["_player_bats"]);
        endif; 

        if(isset($_POST["_player_throws"])) :
        update_post_meta($post->ID, '_player_throws', $_POST["_player_throws"]);
        endif; 

        if(isset($_POST["_player_height"])) :
        update_post_meta($post->ID, '_player_height', $_POST["_player_height"]);
        endif; 

        if(isset($_POST["_player_weight"])) :
        update_post_meta($post->ID, '_player_weight', $_POST["_player_weight"]);
        endif; 

        if(isset($_POST["_player_resides"])) :
        update_post_meta($post->ID, '_player_resides', $_POST["_player_resides"]);
        endif; 

        if(isset($_POST["_player_college"])) :
        update_post_meta($post->ID, '_player_college', $_POST["_player_college"]);
        endif; 
        
        if(isset($_POST["_player_season_year"])) :
        update_post_meta($post->ID, '_player_season_year', $_POST["_player_season_year"]);
        endif; 


}

add_action( 'save_post', 'player_save_meta_box_data' );

function advisory_board_post_type() {

$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'revisions', // post revisions
'post-formats', // post formats
);

$labels = array(
    'name' => _x('Advisory Boards', 'plural'),
    'singular_name' => _x('Advisory Board', 'singular'),
    'menu_name' => _x('Advisory Board', 'admin menu'),
    'name_admin_bar' => _x('Advisory Board', 'admin bar'),
    'add_new' => _x('Add Advisory Board', 'add new'),
    'add_new_item' => __('Add New Advisory Board'),
    'new_item' => __('New Advisory Board'),
    'edit_item' => __('Edit Advisory Board'),
    'view_item' => __('View Advisory Board'),
    'all_items' => __('All Advisory Board'),
    'search_items' => __('Search Advisory Board'),
    'not_found' => __('No Advisory Board found.'),
);

$args = array(
'supports' => $supports,
'labels' => $labels,
'public' => true,
'query_var' => true,
'rewrite' => array('slug' => 'advisory board'),
'has_archive' => false,
'hierarchical' => false,
);
register_post_type('Advisory_Board', $args);
}
add_action('init', 'advisory_board_post_type');


function front_office_post_type() {

$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'revisions', // post revisions
'post-formats', // post formats
);

$labels = array(
    'name' => _x('Front Office Staffs', 'plural'),
    'singular_name' => _x('Front Office Staff', 'singular'),
    'menu_name' => _x('Front Office Staff', 'admin menu'),
    'name_admin_bar' => _x('Front Office Staff', 'admin bar'),
    'add_new' => _x('Add Front Office Staff', 'add new'),
    'add_new_item' => __('Add New Front Office Staff'),
    'new_item' => __('New Front Office Staff'),
    'edit_item' => __('Edit Front Office Staff'),
    'view_item' => __('View Front Office Staff'),
    'all_items' => __('All Front Office Staff'),
    'search_items' => __('Search Front Office Staff'),
    'not_found' => __('No Front Office Staff found.'),
);

$args = array(
'supports' => $supports,
'labels' => $labels,
'public' => true,
'query_var' => true,
'rewrite' => array('slug' => 'front office staff'),
'has_archive' => false,
'hierarchical' => false,
);
register_post_type('Front_Office', $args);
}
add_action('init', 'front_office_post_type');

function coach_post_type() {

$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'revisions', // post revisions
'post-formats', // post formats
);

$labels = array(
    'name' => _x('Coaches', 'plural'),
    'singular_name' => _x('Coach', 'singular'),
    'menu_name' => _x('Coach', 'admin menu'),
    'name_admin_bar' => _x('Coach', 'admin bar'),
    'add_new' => _x('Add Coach', 'add new'),
    'add_new_item' => __('Add New Coach'),
    'new_item' => __('New Coach'),
    'edit_item' => __('Edit Coach'),
    'view_item' => __('View Coach'),
    'all_items' => __('All Coaches'),
    'search_items' => __('Search Coach'),
    'not_found' => __('No Coach found.'),
);

$args = array(
'supports' => $supports,
'labels' => $labels,
'public' => true,
'query_var' => true,
'rewrite' => array('slug' => 'coach'),
'has_archive' => false,
'hierarchical' => false,
);
register_post_type('coach', $args);
}
add_action('init', 'coach_post_type');

/**
 * Adds a box to the main column on the Post add/edit screens.
 */
function advisory_board_add_meta_box() {

        add_meta_box(
                'advisory_board_sectionid', 'Advisory Board Info', 'advisory_board_meta_box_callback', 'advisory_board'
        ); //you can change the 4th paramter i.e. post to custom post type name, if you want it for something else

}

add_action( 'add_meta_boxes', 'advisory_board_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function advisory_board_meta_box_callback() {
         global $post;
                 // Add an nonce field so we can check for it later.
        wp_nonce_field( 'advisory_board_meta_box', 'advisory_board_meta_box_nonce' );

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $ab_order = get_post_meta( $post->ID, '_ab_order', true ); //my_key is a meta_key. Change it to whatever you want
        $ab_position = get_post_meta( $post->ID, '_ab_position', true ); //my_key is a meta_key. Change it to whatever you wantz
        $ab_name = get_post_meta( $post->ID, '_ab_name', true ); //my_key is a meta_key. Change it to whatever you wantz
        ?>

        <?php if(isset($ab_order)){
        ?>
        <div class="row">
        <div class="label">Select the order of display</div>
		
        <div class="fields">
            <select name ="_ab_order" id="_ab_order">
				
            <?php
              for ($i=1; $i<=100; $i++)
              {
                  ?>
                      
					<option name="ab_order" value="<?php echo $i; ?>" <?php selected( $ab_order, $i ); ?>><?php echo $i;?></option>
                  <?php
              }
            ?>
            </select>
        </div>
    </div>
    <br>
    <!-- ab_order ends-->
    <!-- position name-->
    <?php 
  }
    if(isset($ab_position)){
    ?>
    <div class="row">
      <div class="label">Position Name</div>
      <div class="fields">
          <input name="_ab_position" type="text" value="<?php if ($ab_position) { echo $ab_position; }?>">
          </input>
      </div>
    </div>
    <br>
    <?php
    }
    ?>
    <!-- position name ends-->
    <?php
    if(isset($ab_name)){
            ?>
            <div class="row">
                <div class="label">Staff Name</div>
                <div class="fields">
                    <input name="_ab_name" type="text" value="<?php if ($ab_name) { echo $ab_name; }?>">
                </div>
            </div>
        <?php
    }


}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function advisory_board_save_meta_box_data( $post_id ) {

        global $post;
        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( !isset( $_POST['advisory_board_meta_box_nonce'] ) ) {
                return;
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['advisory_board_meta_box_nonce'], 'advisory_board_meta_box' ) ) {
                return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }

        if(isset($_POST["_ab_order"])) :
        update_post_meta($post->ID, '_ab_order', $_POST["_ab_order"]);
        endif;

        if(isset($_POST["_ab_position"])) :
        update_post_meta($post->ID, '_ab_position', $_POST["_ab_position"]);
        endif;
	
        if(isset($_POST["_ab_name"])) :
        update_post_meta($post->ID, '_ab_name', $_POST["_ab_name"]);
        endif;
}

add_action( 'save_post', 'advisory_board_save_meta_box_data' );

/**
 * Adds a box to the main column on the Post add/edit screens.
 */
function front_office_add_meta_box() {


        add_meta_box(
                'front_office_sectionid', 'Front Office Staff Info', 'front_office_meta_box_callback', 'front_office'
        ); //you can change the 4th paramter i.e. post to custom post type name, if you want it for something else

}

add_action( 'add_meta_boxes', 'front_office_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function front_office_meta_box_callback() {
         global $post;
                 // Add an nonce field so we can check for it later.
        wp_nonce_field( 'front_office_meta_box', 'front_office_meta_box_nonce' );

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $fo_order = get_post_meta( $post->ID, '_fo_order', true ); //my_key is a meta_key. Change it to whatever you want
        $fo_position = get_post_meta( $post->ID, '_fo_position', true ); //my_key is a meta_key. Change it to whatever you wantz
        $fo_name = get_post_meta( $post->ID, '_fo_name', true ); //my_key is a meta_key. Change it to whatever you wantz
        ?>

        <?php if(isset($fo_order)){
        ?>
        <div class="row">
        <div class="label">Select the order of display</div>
		
        <div class="fields">
            <select name ="_fo_order" id="_fo_order">
				
            <?php
              for ($i=1; $i<=100; $i++)
              {
                  ?>
                      
					<option name="fo_order" value="<?php echo $i; ?>" <?php selected( $fo_order, $i ); ?>><?php echo $i;?></option>
                  <?php
              }
            ?>
            </select>
        </div>
    </div>
    <br>
    <!-- fo_order ends-->
    <!-- position name-->
    <?php 
  }
    if(isset($fo_position)){
    ?>
    <div class="row">
      <div class="label">Position Name</div>
      <div class="fields">
          <input name="_fo_position" type="text" value="<?php if ($fo_position) { echo $fo_position; }?>">
          </input>
      </div>
    </div>
    <br>
    <?php
    }
    ?>
    <!-- position name ends-->
    <?php
    if(isset($fo_name)){
            ?>
            <div class="row">
                <div class="label">Staff Name</div>
                <div class="fields">
                    <input name="_fo_name" type="text" value="<?php if ($fo_name) { echo $fo_name; }?>">
                </div>
            </div>
        <?php
    }


}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function front_office_save_meta_box_data( $post_id ) {

        global $post;
        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( !isset( $_POST['front_office_meta_box_nonce'] ) ) {
                return;
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['front_office_meta_box_nonce'], 'front_office_meta_box' ) ) {
                return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }

        if(isset($_POST["_fo_order"])) :
        update_post_meta($post->ID, '_fo_order', $_POST["_fo_order"]);
        endif;

        if(isset($_POST["_fo_position"])) :
        update_post_meta($post->ID, '_fo_position', $_POST["_fo_position"]);
        endif;
	
	    if(isset($_POST["_fo_name"])) :
        update_post_meta($post->ID, '_fo_name', $_POST["_fo_name"]);
        endif;
}

add_action( 'save_post', 'front_office_save_meta_box_data' );


/**
 * Adds a box to the main column on the Post add/edit screens.
 */
function coach_add_meta_box() {

        add_meta_box(
                'coach_sectionid', 'Coach Info', 'coach_meta_box_callback', 'coach'
        ); //you can change the 4th paramter i.e. post to custom post type name, if you want it for something else

}

add_action( 'add_meta_boxes', 'coach_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function coach_meta_box_callback() {
         global $post;
                 // Add an nonce field so we can check for it later.
        wp_nonce_field( 'coach_meta_box', 'coach_meta_box_nonce' );

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $coach_order = get_post_meta( $post->ID, '_coach_order', true ); //my_key is a meta_key. Change it to whatever you want
        $coach_position = get_post_meta( $post->ID, '_coach_position', true ); //my_key is a meta_key. Change it to whatever you wantz
        $coach_name = get_post_meta( $post->ID, '_coach_name', true ); //my_key is a meta_key. Change it to whatever you wantz
        ?>

        <?php if(isset($coach_order)){
        ?>
        <div class="row">
        <div class="label">Select the order of display</div>
		
        <div class="fields">
            <select name ="_coach_order" id="_coach_order">
				
            <?php
              for ($i=1; $i<=100; $i++)
              {
                  ?>
                      
					<option name="coach_order" value="<?php echo $i; ?>" <?php selected( $coach_order, $i ); ?>><?php echo $i;?></option>
                  <?php
              }
            ?>
            </select>
        </div>
    </div>
    <br>
    <!-- coach_order ends-->
    <!-- position name-->
    <?php 
  }
    if(isset($coach_position)){
    ?>
    <div class="row">
      <div class="label">Position Name</div>
      <div class="fields">
          <input name="_coach_position" type="text" value="<?php if ($coach_position) { echo $coach_position; }?>">
          </input>
      </div>
    </div>
    <br>
    <?php
    }
    ?>
    <!-- position name ends-->
    <?php
    if(isset($coach_name)){
            ?>
            <div class="row">
                <div class="label">Staff Name</div>
                <div class="fields">
                    <input name="_coach_name" type="text" value="<?php if ($coach_name) { echo $coach_name; }?>">
                </div>
            </div>
        <?php
    }


}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function coach_save_meta_box_data( $post_id ) {

        global $post;
        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( !isset( $_POST['coach_meta_box_nonce'] ) ) {
                return;
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['coach_meta_box_nonce'], 'coach_meta_box' ) ) {
                return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }

        if(isset($_POST["_coach_order"])) :
        update_post_meta($post->ID, '_coach_order', $_POST["_coach_order"]);
        endif;

        if(isset($_POST["_coach_position"])) :
        update_post_meta($post->ID, '_coach_position', $_POST["_coach_position"]);
        endif;
        if(isset($_POST["_coach_name"])) :
        update_post_meta($post->ID, '_coach_name', $_POST["_coach_name"]);
        endif;
}

add_action( 'save_post', 'coach_save_meta_box_data' );

add_filter('woocommerce_add_to_cart_redirect', 'lw_add_to_cart_redirect');
function lw_add_to_cart_redirect() {
 global $woocommerce;
 $lw_redirect_checkout = /*$woocommerce->cart->get_checkout_url()*/wc_get_checkout_url();
 return $lw_redirect_checkout;
}

add_filter( 'woocommerce_checkout_fields' , 'custom_checkout_fields' );
function custom_checkout_fields( $fields ) {
unset($fields['billing']['billing_phone']);
unset($fields['billing']['billing_company']);
unset($fields['shipping']['shipping_company']);
unset($fields['order']['order_comments']);
return $fields;
}

add_filter( 'default_checkout_billing_country', 'change_default_checkout_country' );

function change_default_checkout_country() {
  return 'US'; // country code
}

?>