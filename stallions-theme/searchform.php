<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 */

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */
//$twentytwenty_unique_id = twentytwenty_unique_id( 'search-form-' );

//$twentytwenty_aria_label = ! empty( $args['label'] ) ? 'aria-label="' . esc_attr( $args['label'] ) . '"' : '';
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/news/' ) ); ?>">
	<!--<label for="<?php echo esc_attr( $twentytwenty_unique_id ); ?>">-->
		<span class="screen-reader-text"><?php _e( 'Search news:' ); // phpcs:ignore: WordPress.Security.EscapeOutput.UnsafePrintingFunction -- core trusts translations ?></span>
		<input type="search" class="search-field" value="<?php echo get_search_query(); ?>" name="s" />
	<!--</label>-->
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button'); ?>" />
</form>
