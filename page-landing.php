<?php
/**
 * iBiology in.
 *
 * This file adds the speakers page template to the iBiology Theme.
 *
 * @package iBiology
 * @author  Lobsang Wangdu
 * @license GPL-2.0+
 * @link    http://www.ibiology/
 */

//* Template Name: Landing

//* Add landing page body class to the head
add_filter( 'body_class', 'ibiology_add_body_class' );
function ibiology_add_body_class( $classes ) {

	$classes[] = 'landing-page';

	return $classes;

}

//* Remove Skip Links
remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );

//* Dequeue Skip Links Script
add_action( 'wp_enqueue_scripts', 'ibiology_dequeue_skip_links' );
function ibiology_dequeue_skip_links() {

	wp_dequeue_script( 'skip-links' );

}

//* Force full width content layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

//* Remove site header elements
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

//* Remove navigation
remove_theme_support( 'genesis-menus' );

//* Remove breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

//* Remove footer widgets
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

//* Remove site footer elements
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

//* Run the Genesis loop
genesis();
