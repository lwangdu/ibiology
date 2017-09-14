<?php

	// Search template
	
	
// Set up the post excerpt with or without featured image, depending on the kind of
// post it is.
	
function ibio_setup_result(){

	add_action( 'genesis_entry_content', 'genesis_do_post_image' , 8 );

	global $post;
	if ( $post->post_type == IBioTalk::$post_type ){
		$videos = get_field( 'videos' );
		if ( is_array( $videos ) && count( $videos ) > 1 ){
			remove_action( 'genesis_entry_content', 'genesis_do_post_image' , 8 );
		} 
	}  // should we do this for sessions?
}
	
	
/* ------------------------  Page Rendering -----------------*/

add_action( 'genesis_entry_header', 'ibio_setup_result', 5);	
genesis();
