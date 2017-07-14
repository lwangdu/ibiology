<?php
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
//* Add support for post formats
add_theme_support( 'post-formats', array(
	'video'
) );

// Add post-formats to all post types
add_action( 'init', 'gfv_add_post_formats_to_post_types', 11 );
function gfv_add_post_formats_to_post_types() {
  $post_types = gvf_get_post_types();
  foreach( $post_types as $post_type ) {
	  add_post_type_support( $post_type, 'post-formats' );
  }
}
