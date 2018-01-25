<?php

/**
Template Name: Custom Blog
*/

add_filter( 'body_class', 'blog_page_body_class' );
function blog_page_body_class( $classes ) {
   $classes[] = 'custom-blog';
   return $classes;
}

add_action('genesis_loop', 'genesis_standard_loop', 5);

genesis();