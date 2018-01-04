<?php

// template part to display a single video in a list.  Must be inside a loop
// to be able to use this.

// Get the elements needed to display.

global $post;

$short_title = get_field( 'short_title' );

$title = empty( $short_title ) ? $post->post_title : $short_title;

?>

