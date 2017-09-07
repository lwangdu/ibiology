<?php

function ibio_facet_start(){
	echo '<div class="facetwp-template">';
}

function ibio_facet_end(){
	echo '</div><!--Facet Container -->';
}

function ibio_talks_filter_widget_area(){
	dynamic_sidebar( 'sidebar_talks_filter' );
}

function ibio_archive_description( $heading = '', $intro_text = '', $context = '' ) {

	if ( $context && $intro_text ) {
		echo apply_filters('the_content', $intro_text);
	}

}


/* -----------------  Page Rendering ---------------------- */

// force full width layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

add_action( 'wp_head', 'ibio_content_archive_setup' );

remove_action('genesis_archive_title_descriptions', 'genesis_do_archive_headings_intro_text', 12);
add_action('genesis_archive_title_descriptions', 'ibio_archive_description', 12, 3);

add_action( 'genesis_archive_title_descriptions', 'ibio_talks_filter_widget_area', 14);

add_action( 'genesis_before_loop', 'ibio_facet_start', 100);
add_action( 'genesis_after_loop', 'ibio_facet_end' );

genesis();