<?php
/**
 * @package   PACKAGE_NAME_GOES_HERE
 * @author    PLUGIN_AUTHOR_NAME
 * @license   GPL-2.0+
 * @link      https://engagementnetwork.org
 * @copyright 2016 CARES Network
 */

/**
 * Output the contents of the shortcode.
 *
 * @since   1.0.0
 *
 * @param   ... Add description of possible params here.
 *
 * @return  html
 */
function PLUGIN_PREFIX_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'var'  => 'default_value',
		'var2' => ''
		), $atts );

	wp_enqueue_script( PLUGIN_PREFIX_get_plugin_slug() . '-plugin-script' );

	ob_start();
	// Create the output. 
	// Shortcode output has to go into an output buffer
	return ob_get_clean();
}
add_shortcode( 'PLUGIN_SLUG_GOES_HERE', 'PLUGIN_PREFIX_shortcode' );