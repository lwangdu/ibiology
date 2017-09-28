<?php

  /* Functions related to content */

//* content filters (move to its own place soon)

function ibio_content_archive_setup(){
	remove_action( 'genesis_before_loop', 'ibio_breadcrumbs');
	add_action( 'genesis_before_loop', 'ibio_breadcrumbs', 8);
  
  add_action( 'body_class', 'ibio_grid_body_class' );
  add_filter( 'genesis_post_title_text', 'ibio_talk_short_title' );
  remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
  // move the title below the image
  remove_action( 'genesis_entry_header', 'genesis_do_post_title');
  add_action( 'genesis_entry_footer', 'genesis_do_post_title');
  remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
  remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
}

function ibio_talk_short_title($title){

  global $post;
  $short_title = get_field( 'short_title' );
  $new_title = empty( $short_title ) ? $title : $short_title;
  return $new_title;
}

function ibio_grid_body_class($classes){
  $classes[] = 'grid-listing';
  return $classes;
}

// Get the playlists for a talk.  Returns an array of playlists.

function ibio_playlists_talk($talk_id=null){
	if ( empty( $talk_id ) ) {
		$talk = get_queried_object();
		if ( isset( $talk->ID ) ){
			$talk_id = $talk->ID;
		} else {
			return null;
		}
	}
	
	
  $playlist_talks = new WP_Query( array (
        'post_type' => IbioPLaylist::$post_type,
        'posts_per_page'  => 4,
        'connected_type' => 'playlist_to_talks'
     ) );
	
}

function ibio_ed_resources(){
	ibio_get_template_part( 'shared/related-resources', 'educator' );
	
}

function ibio_related_content(){

	ibio_get_template_part( 'shared/related', 'talks-by-category' );

  ibio_get_template_part( 'shared/related', 'resources' );
	
	get_template_part('parts/primary-playlist');
	
}

/*
 * ibio_expandable_section
 * @param $content Input content
 * @returns the content wrapped in HTML to create an expandable section later.
*/
function ibio_expandable_section( $content )
{
    return '<div class="expandable">' . $content . '</div>';
}
