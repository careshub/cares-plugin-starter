<?php
/**
 *
 * @package   PACKAGE_NAME_GOES_HERE
 * @author    PLUGIN_AUTHOR_NAME
 * @license   GPL-2.0+
 * @link      https://engagementnetwork.org
 * @copyright 2016 CARES Network
 *
 * @wordpress-plugin
 * Plugin Name:       PLUGIN_NAME_HUMAN_READABLE
 * Plugin URI:        @TODO
 * Description:       Adds tools to the CARES Network sites
 * Version:           1.0.0
 * Author:            PLUGIN_AUTHOR_NAME
 * Text Domain:       PLUGIN_SLUG_GOES_HERE
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/careshub/PLUGIN_SLUG_GOES_HERE
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

function PLUGIN_PREFIX_init() {

	$base_path = plugin_dir_path( __FILE__ );

	// Functions
	require_once( $base_path . 'includes/functions.php' );

	// Template output functions
	require_once( $base_path . 'public/views/template-tags.php' );
	require_once( $base_path . 'public/views/shortcodes.php' );

	// The main class
	require_once( $base_path . 'public/class-PLUGIN_SLUG_GOES_HERE.php' );
	$PLUGIN_PREFIX = new PLUGIN_PREFIX();
	// Add the action and filter hooks.
	$PLUGIN_PREFIX->hook_actions();

	// Admin and dashboard functionality
	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		require_once( $base_path . 'admin/class-PLUGIN_SLUG_GOES_HERE-admin.php' );
		$PLUGIN_PREFIX_admin = new PLUGIN_PREFIX_Admin();
		// Add the action and filter hooks.
		$PLUGIN_PREFIX_admin->hook_actions();
	}

}
add_action( 'init', 'PLUGIN_PREFIX_init' );

/*
 * Helper function.
 * @return Fully-qualified URI to the root of the plugin.
 */
function PLUGIN_PREFIX_get_plugin_base_uri(){
	return plugin_dir_url( __FILE__ );
}

/*
 * Helper function.
 * @TODO: Update this when you update the plugin's version above.
 *
 * @return string Current version of plugin.
 */
function PLUGIN_PREFIX_get_plugin_version(){
	return '1.0.0';
}
function PLUGIN_PREFIX_get_plugin_slug(){
	return 'PLUGIN_SLUG_GOES_HERE';
}