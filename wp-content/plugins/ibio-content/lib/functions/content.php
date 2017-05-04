<?php

  /* Functions related to content */

//* content filters (move to its own place soon)

function ibio_content_archive_setup(){
  add_action( 'body_class', 'ibio_grid_body_class' );
  add_filter( 'genesis_post_title_text', 'ibio_talk_short_title' );
  remove_action( 'genesis_entry_header', 'genesis_post_info', 8 );
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