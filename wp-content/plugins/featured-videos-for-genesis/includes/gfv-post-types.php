<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
//get all post type names
function gvf_get_post_types() {
	$args = array(
   'public'   => true,
   '_builtin' => false
	);

	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'

	$post_types = get_post_types( $args, $output, $operator );
  $post_types = array_merge( $post_types, array( 'post' => 'post' ) );
  if ( has_filter( 'gfv_post_types' ) ){
    $post_types = apply_filters( 'gfv_post_types', $post_types );
  }
	return $post_types;
}
function gfv_modify_post_types( $post_types ) {
  return $post_types;
}
add_filter( 'gfv_post_types', 'gfv_modify_post_types' );
