<?php


/*
Template Name: Advanced Search
*/


add_action( 'genesis_before_loop', 'metro_do_search_title' );
/**
 * Echo the title with the search term.
 *
 * @since 1.9.0
 */
function genesis_do_search_title() {

	$title = sprintf( '<h1 class="archive-title">%s %s</h1>', apply_filters( 'genesis_search_title_text', __( 'Search Results for:', 'genesis' ) ), get_search_query() );

	echo apply_filters( 'genesis_search_title_output', $title ) . "\n";
                    

}



function metro_do_search_title() {

	$title = sprintf( '<h1 class="archive-title">%s %s</h1>', apply_filters( 'metro_search_title_text', __( 'Search Results for:', 'metro' ) ), get_search_query() );

	echo apply_filters( 'metro_search_title_output', $title ) . "\n";
                    

}




genesis();