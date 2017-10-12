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

// removes the hooks for post meta and post info on pages where this is called.
function ibio_setup_single(){
    remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
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

function ibio_ed_resources(){
	ibio_get_template_part( 'shared/related-resources', 'educator' );
	
}

// show the "related content" items, usually grouped together after a talk.
function ibio_related_content(){

    if (is_singular(IBioTalk::$post_type)){
        $primary_related_category = get_field('related_talks');
        if ( !$primary_related_category ){
            ibio_get_template_part('shared/primary', 'playlist');
        } else {
            ibio_get_template_part( 'shared/related', 'talks-by-category' );
        }
        ibio_get_template_part( 'shared/related', 'resources' );
    } else {

        ibio_get_template_part( 'shared/related', 'talks-by-category' );
        ibio_get_template_part( 'shared/related', 'resources' );
        ibio_get_template_part('shared/primary', 'playlist');
    }


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

/*******  FACET-WP items ****************/

function ibio_facet_start(){
    echo '<div class="facetwp-template">';
}

function ibio_facet_end(){
    echo '</div><!--Facet Container -->';
}
