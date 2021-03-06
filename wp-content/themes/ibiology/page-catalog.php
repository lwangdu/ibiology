<?php
/**
 * Template Name: Resource Catalog
 *
 */

function ibio_data_tables_scripts(){

	wp_enqueue_style( 'datatables',
		'https://cdn.datatables.net/v/dt/dt-1.10.21/b-1.6.2/b-html5-1.6.2/r-2.2.5/sp-1.1.1/sl-1.3.1/datatables.min.css');

	wp_enqueue_script('datatables', 'https://cdn.datatables.net/v/dt/dt-1.10.21/b-1.6.2/b-html5-1.6.2/r-2.2.5/sp-1.1.1/sl-1.3.1/datatables.min.js', array('jquery-ui-core'));

	wp_enqueue_script('ibiology-content', get_stylesheet_directory_uri() . '/assets/js/ibio-theme.js', array('datatables'), '1.1.0', true);

}


add_action( 'wp_enqueue_scripts', 'ibio_data_tables_scripts' );
if (function_exists('ibio_catalog') ) {
	add_action( 'genesis_loop', 'ibio_catalog' );
}

genesis();
