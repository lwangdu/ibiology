<?php
/**
 * Template Name: Talk With Resources
 */

global $talk_to_display;

function ibio_setup_talk_display(){
	global $talk_to_display;

	if ( isset( $_GET['tid'] ) && is_numeric( $_GET['tid'] ) ) {
		$talk_to_display = get_post($_GET['tid']);
	}

}

function ibio_talk_title($title){
	global $talk_to_display;
	if ( !empty($talk_to_display) ) return $talk_to_display->post_title;
}

function ibio_extract_talk(){
	global $talk_to_display;

	if (function_exists('ibio_talk_for_educators' ) ) {
		ibio_talk_for_educators( $talk_to_display );

	}
}

// full width page
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
add_filter('the_title', 'ibio_talk_title');

add_action('wp_head', 'ibio_setup_talk_display');

//remove_action('genesis_entry_header', 'genesis_do_post_title');
add_filter( 'genesis_post_title_text', 'ibio_talk_title', 20);

remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'ibio_extract_talk');

genesis();