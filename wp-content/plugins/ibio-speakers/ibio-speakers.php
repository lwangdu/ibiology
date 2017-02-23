<?php 
/*Plugin Name: iBio Speakers
Description: This plugin registers the 'speakers' taxonomy and applies it to the basic post type.
Version: 1.0
License: GPLv2
*/

// register two taxonomies to go with the post type
function ibio_speakers_register_taxonomy() {
	// set up speakers
	$speakers = array(
		'name'              => 'Speakers',
		'singular_name'     => 'Speaker',
		'search_items'      => 'Search Speakers',
		'all_items'         => 'All Speakers',
		'edit_item'         => 'Edit Speaker',
		'update_item'       => 'Update Speaker',
		'add_new_item'      => 'Add New Speaker',
		'new_item_name'     => 'New Speaker',
		'menu_name'         => 'Speakers'
	);
	// register taxonomy
	register_taxonomy( 'speakers', 'post', array(
		'hierarchical' => true,
		'labels' => $speakers,
		'query_var' => true,
		'show_admin_column' => true,
		'rewrite' => array(
            'slug' => 'speaker',
            'hierarchical' => true,
        )
	) );
}
add_action( 'init', 'ibio_speakers_register_taxonomy' );
?>