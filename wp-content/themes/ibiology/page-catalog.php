<?php
/**
 * Template Name: Resource Catalog
 *
 */

function ibio_data_tables_scripts(){

	wp_enqueue_style( 'datatables',
		'https://cdn.datatables.net/v/dt/dt-1.10.21/b-1.6.2/b-html5-1.6.2/r-2.2.5/sp-1.1.1/sl-1.3.1/datatables.min.css');

	wp_enqueue_script('datatables', 'https://cdn.datatables.net/v/dt/dt-1.10.21/b-1.6.2/b-html5-1.6.2/r-2.2.5/sp-1.1.1/sl-1.3.1/datatables.min.js', array('jquery-ui-core'));

}

// Get everything that has educator resources (sessions and talks) and display them in a table.
function ibio_catalog(){

	$args = array(
		'post_type' => array( IBioTalk::$post_type, IBioSession::$post_type),
		'post_status' => 'publish',
		'meta_key' => 'has_educator_resources',
		'posts_per_page' => -1

	);

	global $talks;

	$talks = new WP_Query( $args );

	//var_dump( $talks);

	if ( $talks->have_posts() && function_exists( 'ibio_get_template_part' ) ){
		ibio_get_template_part( 'shared/expanded-talks', 'table');
	}

}


add_action( 'wp_enqueue_scripts', 'ibio_data_tables_scripts' );
add_action( 'genesis_loop', 'ibio_catalog' );

genesis();
