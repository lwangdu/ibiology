<?php

	// Search template
	
	
// Set up the post excerpt with or without featured image, depending on the kind of
// post it is.
	
function ibio_setup_result(){

    // remove postmeta and postinfo on all search results.
    remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
    remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

    add_action( 'genesis_entry_header', 'ibio_post_type_label', 6);

    add_action( 'genesis_entry_content', 'genesis_do_post_image' , 8 );


    global $post;
	if ( $post->post_type == IBioTalk::$post_type ){
		$videos = get_field( 'videos' );
		if ( is_array( $videos ) && count( $videos ) > 1 ){
			remove_action( 'genesis_entry_content', 'genesis_do_post_image' , 8 );
		} 
	}  // should we do this for sessions?
}
	
function ibio_search_sidebar(){
	 dynamic_sidebar( 'sidebar_search' );
}

function ibio_search_loop(){


}

function ibio_post_type_label(){
    global $post;

    $type = get_post_type_object($post->post_type);
    $labels = $type->labels;

    ?>

    <aside class="post-type-label"><?php echo $labels->singular_name; ?></aside>

    <?php

}

/* ------------------------  Page Rendering -----------------*/

add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );
add_action( 'genesis_entry_header', 'ibio_setup_result', 5);
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'ibio_search_sidebar' );
//remove_action( 'genesis_loop', 'genesis_do_loop');
add_action( 'genesis_loop', 'ibio_search_loop');
genesis();
