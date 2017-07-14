<?php
/*
Plugin Name: Featured Videos for Genesis
Plugin URI: https://scottdeluzio.com
Description: Adds a video post format with video thumbnail replacing featured image.
Version: 1.0.2
Author: Scott DeLuzio
Author URI: https://scottdeluzio.com
Text Domain: genesis-featuerd-video
*/

if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}

/* Check if Genesis is Installed
--------------------------------------------- */
function gfv_require() {
	$files = array(
		'gfv'
	); //array for future use

	foreach( $files as $file ) {
		require plugin_dir_path( __FILE__ ) . 'lib/' . $file . '.php';
	}
  $gfv = new Genesis_Featured_Video();
  $gfv->gfv_run();
}
add_action( 'admin_init', 'gfv_require' );

/* Load text domain
--------------------------------------------- */
add_action('plugins_loaded', 'gfv_load_textdomain');
function gfv_load_textdomain() {
	load_plugin_textdomain( 'genesis-featured-video', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

add_action( 'wp_enqueue_scripts', 'gfv_styles', 100 );
function gfv_styles() {
  wp_register_style( 'gfv-styles', plugins_url( '/css/gfv-style.css', __FILE__ ) );
  wp_enqueue_style( 'gfv-styles' );
}

/*
 * Includes for Genesis Footer Editor Plugin
 */
if ( ! defined( 'GENESIS_FEATURED_VIDEO' ) ) {
  define( 'GENESIS_FEATURED_VIDEO', __FILE__ );
}
if( ! defined( 'GENESIS_FEATURED_VIDEO_PLUGIN_DIR' ) ) {
 	define( 'GENESIS_FEATURED_VIDEO_PLUGIN_DIR', dirname( __FILE__ ) );
}

/*
 * Includes for Genesis Featured Video
 */

/* Video Format */
include( GENESIS_FEATURED_VIDEO_PLUGIN_DIR . '/includes/video-format.php' );
/* Video Image */
include( GENESIS_FEATURED_VIDEO_PLUGIN_DIR . '/includes/video-image.php' );
/* Video Meta Box */
include( GENESIS_FEATURED_VIDEO_PLUGIN_DIR . '/includes/gfv-metabox.php' );
/* Video Settings */
include( GENESIS_FEATURED_VIDEO_PLUGIN_DIR . '/includes/gfv-settings.php' );
/* Post Types */
include( GENESIS_FEATURED_VIDEO_PLUGIN_DIR . '/includes/gfv-post-types.php' );
